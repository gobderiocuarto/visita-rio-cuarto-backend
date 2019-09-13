<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Soporte para transacciones 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Para Redireccionar mediante ancla
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Address;
use App\AddressType;
use App\Category;
use App\Organization;
use App\Place;
use App\Space;
use App\Street;
use App\Zone;

use \Conner\Tagging\Model\Tag;

use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Requests\OrganizationUpdateRequest;

use Intervention\Image\ImageManagerStatic as Image;

class OrganizationController extends Controller
{

    public function __construct() {

        // $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
                    
        $filter = (object)[];
        $filter->search = '';
        $filter->category = '';

        $appends = array();

        $categories = Category::with('category.category')->orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        $organizations = Organization::with('category.category')->orderBy('organizations.name', 'ASC');
        
        if (($request->search != '')) {
            $organizations = $organizations->where('organizations.name', 'like', '%'.$request->search.'%' );
            $filter->search = $request->search;

            $appends ['search'] = $request->search;
        }

        // dd($request->category);

        if (((int)$request->category != 0 )) {
            $organizations = $organizations->join ('categories','categories.id','organizations.category_id')
            ->where(function ($query) use ($request) {
                $query->where ('categories.id', '=', $request->category)
                ->orWhere('categories.category_id', '=', $request->category);
            })
            ->select('organizations.*');

            $filter->category = $request->category;
            $appends ['category'] = $request->category;
        }

        $organizations = $organizations->paginate();
        // $organizations->withPath('custom/url');
        $organizations->appends((array)$filter);
        return view('admin.organizations.index', compact('filter', 'categories', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        $spaces = Space::orderBy('name', 'ASC')->get();
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        return view('admin.organizations.create', compact('categories','spaces','streets', 'zones') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationStoreRequest $request)
    {

        $organization = Organization::create($request->all());

        $tags = explode(',', $request->get('tags'));
        $organization->tag($tags);

        foreach ($organization->tags as $tag) {
           $tag->setGroup('Servicios');
        }

        $organization->update();

        if ($organization) {
            return redirect()->route('organizations.edit', $organization->id)->with('message', 'Organización creada con éxito');
        } else {
            return redirect()->back()->withErrors('Error al crear la organización');
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    
    {
        $organization = Organization::findOrFail($id);
        // $organization = Organization::with('places.placeable')->findOrFail($id);
        // echo ('<pre>');print_r($organization);echo ('</pre>'); exit();
        
        // foreach ($organization->places as $key => $place) {
        //     echo ('<pre>');print_r($place->placeable);echo ('</pre>'); exit();
        //     # code...
        // }

        //dd($organization->spaces);

        $tags = implode(', ', $organization->tagNames());

        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();

        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        $spaces = Space::orderBy('name', 'ASC')->get();
       
        $streets = $this->getStreets();

        $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();

        return view('admin.organizations.edit', compact('organization','tags', 'categories','spaces','zones', 'addresses_types', 'streets'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationUpdateRequest $request, $id)
    {

        // dd($request->all());

        $organization = Organization::findOrFail($id);

        $tags = explode(',', $request->get('tags'));
        $organization->retag($tags);

        foreach ($organization->tags as $tag) {
           $tag->setGroup('Servicios');
        }

        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $folder_img = 'organizations/'.$organization->id.'/';
            $thumb_img = $folder_img.'thumbs/';

            // Borrar archivos anteriores, si existen
            if($organization->file) {

                if (Storage::exists($folder_img.$organization->file->file_path) ) {
                    Storage::delete($folder_img.$organization->file->file_path);
                    Storage::delete($thumb_img.$organization->file->file_path);
                }  
                $organization->file->delete();
            }

            // Renombrar archivo entrante
            $new_img = $this->renameFile($request->file('file'));

            if( $path = Storage::putFileAs($folder_img, $request->file('file'), $new_img) ) {

                Storage::makeDirectory($thumb_img);

                $img = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$new_img );                      

                $organization->file()->create(['file_path'=> $new_img, 'file_alt'=> $request->get('file_alt') ]);
                
            }
            
        } else {
            $organization->file()->update(['file_alt'=> $request->get('file_alt')]);
        }

        $organization->fill($request->all())->save();

        return redirect('admin/organizations/' . $organization->id.'/edit#organization_tab')->with('message', 'Organización actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id)->delete();
        return back()->with('message', 'Organización eliminada correctamente');
    }



    public function storePlace(Request $request, Organization $organization)
    {

        // dd($request->all());exit();

        # Tipo de ubicación
        $address_type_id = $request->get('address_type');

        // Determino el nombre del tipo de ubicación
        if ($request->get('address_type') === "0"){ // nuevo tipo de ubicación

            $address_type_name =  $request->get('address_type_name');

        } else {
            // Selecciona el tipo de ubicación del listado de la db
            $address_type_name = AddressType::find($address_type_id)->name;
            
        }
        # END Tipo de ubicación

        # Start transaction
        // DB::beginTransaction();
        if ($request->place_id == 0) { // crear nueva ubicación

            if($request->space) { // asociar un espacio

                // dd($request->all());exit();
                # Determinar si el espacio ya esta asociado a la org a traves de una ubicación
                $exist_place = Place::where('organization_id', $organization->id)
                            ->where('placeable_type', 'App\Space')
                            ->where('placeable_id', $request->space)
                            ->get()->toArray();
                if ($exist_place) {

                    // DB::rollBack();
                    return Redirect::to(URL::previous() . "#places_tab")->withErrors('La ubicación elegida ya se encuentra asociada');

                } else { // address

                    $place = Place::create([
                        'organization_id' => $organization->id,
                        'placeable_type' => 'App\Space',
                        'placeable_id' => $request->get('space'),
                        'address_type_id' => $request->get('address_type'),
                        'address_type_name' => $request->get('address_type_name'),
                        'apartament' => $request->get('apartament')
                    ]);

                }
            
            } else { // asociar una address

                //Planteear reutilizacion de Address con mismo indice compuesto de calle y numero
                $address = Address::create($request->all());

                $place = Place::create([
                    'organization_id' => $organization->id,
                    'placeable_type' => 'App\Address',
                    'placeable_id' => $address->id,
                    'address_type_id' => $request->get('address_type'),
                    'address_type_name' => $request->get('address_type_name'),
                    'apartament' => $request->get('apartament')
                ]);

            }

            // DB::rollBack();
            return redirect('admin/organizations/'. $organization->id.'/edit#places_tab')
                        ->with('message', 'Se asoció correctamente la ubicación');


        } else { // edicion de ubicacion

            $place = Place::findOrFail($request->get('place_id'));

            if($request->space) { // asociar space a org

                # Determinar si el espacio ya esta asociado a la org a traves de una ubicación
                $exist_place = Place::where('organization_id', $organization->id)
                            ->where('placeable_type', 'App\Space')
                            ->where('placeable_id', $request->get('space'))
                            ->where('id', '!=', $request->get('place_id'))
                            ->get()->toArray();

                if ($exist_place) {

                    // DB::rollBack();
                    return Redirect::to(URL::previous() . "#places_tab")->withErrors('La ubicación elegida ya se encuentra asociada');

                } else {

                    $place->update([
                        'organization_id' => $organization->id,
                        'placeable_type' => 'App\Space',
                        'placeable_id' => $request->get('space'),
                        'address_type_id' => $request->get('address_type'),
                        'address_type_name' => $request->get('address_type_name'),
                        'apartament' => $request->get('apartament')
                    ]);
                    
                }

            
            } else { // asociar address a org

                //Planteear reutilizacion de Address con mismo indice compuesto de calle y numero
                $address = Address::create($request->all());

                $result = $place->update([
                    'organization_id' => $organization->id,
                    'placeable_type' => 'App\Address',
                    'placeable_id' => $address->id,
                    'address_type_id' => $request->get('address_type'),
                    'address_type_name' => $request->get('address_type_name'),
                    'apartament' => $request->get('apartament')
                ]);
                
            }
            // DB::rollBack();
            return redirect('admin/organizations/'. $organization->id.'/edit#places_tab')
                    ->with('message', 'Se actualizó correctamente la ubicación');


        }

    }


    public function destroyPlace($org_id, $place_id)
    {
        // $organization = Organization::findOrFail($org_id);
        // $organization->spaces()->detach($space_id);

        // DB::beginTransaction();

        $place = Place::where('id', $place_id)->where('organization_id', $org_id)->delete();

        // DB::rollBack();

        if ($place) {
            return redirect('admin/organizations/' . $org_id.'/edit#places_tab')->with('message', 'Ubicación eliminada correctamente');
        } else {
            return redirect('admin/organizations/' . $org_id.'/edit#places_tab')->withErrors('Error al eliminar la ubicación');
        }

    }



    // public function destroyAddress($org_id, $address_id)
    // {
        
    //     $organization = Organization::findOrFail($org_id);
    //     $organization->addresses()->where('addresses.id', $address_id)->delete();

    //     // $address = Address::join('address_organization', 'addresses.id', '=', 'address_organization.address_id')
    //     //     ->where('address_organization.organization_id', $org_id)
    //     //     ->where('address_organization.address_id', $address_id)
    //     //     ->delete();

    //     return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->with('message', 'Ubicación eliminada correctamente');
    // }



}