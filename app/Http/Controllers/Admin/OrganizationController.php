<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Soporte para transacciones 
use Illuminate\Support\Facades\DB;

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

# Imagenes
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use App\Traits\ImageTrait;

class OrganizationController extends Controller
{

    use ImageTrait;
    
    public function __construct() {

        $this->large_width     = 1200; 
        $this->medium_width    = 700;
        $this->small_width     = 200; 

        $this->folder_img     = 'organizations/';

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
        
        $tags = implode(', ', $organization->tagNames());

        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();

        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        $spaces = Space::orderBy('name', 'ASC')->get();

        // $containers = Place::with('organization')->where('container', 'is-container')->get();

        $containers = Place::join('organizations', 'organizations.id', 'places.organization_id')
        ->where('container', 'is-container')
        ->orderBy('organizations.name', 'ASC')
        ->select('organizations.name')
        ->select('places.*')
        ->get();

        // echo ('<pre>');print_r($containers);echo ('</pre>'); exit();
       
        $streets = $this->getStreets();

        $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();

        return view('admin.organizations.edit', compact('organization','tags', 'categories','spaces','containers','zones', 'addresses_types', 'streets'));
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

            // $folder_img = 'organizations/'.$organization->id.'/';
            // $thumb_img = $folder_img.'thumbs/';

            // $this->folder_img     = 'organizations/';

            $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $request->file('file'));

            if (!in_array($mime, $this->file_types)) {
                return back()->withErrors('Tipo de archivo no permitido. (Sólo se permiten archivos JPG, PNG, GIF)');
            }

            # Generar el nombre final del archivo (metodo en Controller.php)
            $new_file_name = $this->renameFile($request->file('file'));

            #------------------------------
            # Almacenar archivos
            #------------------------------

            $result = $this->loadImages($request, $new_file_name);
            
            if(!$result) {
                // DB::rollBack();
                return back()->withErrors('Se produjo algún error al actualizar la imagen. Revise los cambios realizados');
            
            } else {

                 #----------------------------------------
                # Borrar archivos anteriores, si existen
                #----------------------------------------

                if($organization->file) {
                    # Borrar archivos
                    $result_del = $this->deleteImages($organization->file->file_path);

                    # Borrar en DB
                    $delete_file = $organization->file->delete();
                    
                }

                # Almacenar nueva imagen en DB
                $result = $organization->file()->create(['file_path'=> $new_file_name, 'file_alt'=> $request->get('file_alt') ]);
            
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
        if ($request->place == 0) { // crear nueva ubicación - place

            if($request->space) { // asociar un espacio

                // # Determinar si el espacio ya esta asociado a la org a traves de una ubicación
                // $exist_place = Place::where('organization_id', $organization->id)
                //             ->where('placeable_type', 'App\Space')
                //             ->where('placeable_id', $request->space)
                //             ->get()->toArray();
                // if ($exist_place) {

                //     // DB::rollBack();
                //     return Redirect::to(URL::previous() . "#places_tab")->withErrors('La ubicación elegida ya se encuentra asociada');

                // } else { // address

                //     $place = Place::create([
                //         'organization_id' => $organization->id,
                //         'placeable_type' => 'App\Space',
                //         'placeable_id' => $request->get('space'),
                //         'address_type_id' => $request->get('address_type'),
                //         'address_type_name' => $address_type_name,
                //         'apartament' => $request->get('apartament')
                //     ]);

                // }
            
            } else { // asociar una nueva address

                $address = Address::create($request->all());
                
                $place = Place::create([
                    'place_id' => $request->get('place_id'),
                    'organization_id' => $organization->id,
                    'placeable_type' => 'App\Address',
                    'placeable_id' => $address->id,
                    'address_type_id' => $request->get('address_type'),
                    'address_type_name' => $address_type_name,
                    'apartament' => $request->get('apartament')
                ]);

                //Planteear reutilizacion de Address con mismo indice compuesto de calle y numero

            }

            // DB::rollBack();
            return redirect('admin/organizations/'. $organization->id.'/edit#places_tab')
                        ->with('message', 'Se asoció correctamente la ubicación');


        } else { // edicion de ubicacion-place existente

            $place = Place::findOrFail($request->get('place'));

            if($request->space) { // se requiere asociar el place a espacio

                // # Comprobar si el espacio ya esta asociado a la org a traves de una ubicación
                // $exist_place = Place::where('organization_id', $organization->id)
                //             ->where('placeable_type', 'App\Space')
                //             ->where('placeable_id', $request->get('space'))
                //             ->where('id', '!=', $request->get('plac'))
                //             ->get()->toArray();

                // if ($exist_place) {
                //     // DB::rollBack();
                //     return Redirect::to(URL::previous() . "#places_tab")->withErrors('La ubicación elegida ya se encuentra asociada');

                // } else {

                //     $place->update([
                //         'organization_id' => $organization->id,
                //         'placeable_type' => 'App\Space',
                //         'placeable_id' => $request->get('space'),
                //         'address_type_id' => $request->get('address_type'),
                //         'address_type_name' => $address_type_name,
                //         'apartament' => $request->get('apartament')
                //     ]);
                    
                // }

            
            } else { // ser requiere asociar place a address

                
                // Analizar reutilizacion de Address con mismo indice compuesto de calle y numero 
                # vincula a Address
                # El Place estaba previamente asociado a un space?
                if ($place->placeable_type == 'App\Space') {
                    
                    $address = Address::create($request->all());
                    $address_id = $address->id;
                    
                } else {
                    
                    // dd($request->all());exit();

                    $place->placeable->update($request->all());
                    // dd($request->all());exit();
                    $address_id = $place->placeable->id;                    

                }

                # Request de pasar una ubicacion contenedora a NO CONTENEDORA 
                if ( ($request->get('container') != 'is-container') && ($place->container == 'is-container') ) {

                    $child_places = Place::where('place_id', $place->id )->get();
                    // echo ('<pre>');print_r($child_places);echo ('</pre>'); exit();
                    foreach ($child_places as $key => $child) {
                        $child->update([
                            "place_id" => NULL,
                        ]);
                        // echo ('<pre>');print_r($child->organization->name);echo ('</pre>'); exit();
                    }
                }

                $result = $place->update([
                    'place_id' => $request->get('place_id'),
                    'organization_id' => $organization->id,
                    'placeable_type' => 'App\Address',
                    'placeable_id' => $address_id,
                    'container' => $request->get('container'),
                    'address_type_id' => $request->get('address_type'),
                    'address_type_name' => $address_type_name,
                    'apartament' => $request->get('apartament')
                ]);
                
                // echo ('<pre>');print_r($place->placeable);echo ('</pre>'); exit();
                
            }
            // DB::rollBack();
            return redirect('admin/organizations/'. $organization->id.'/edit#places_tab')
                    ->with('message', 'Se actualizó correctamente la ubicación');
        }

    }


    public function destroyPlace($org_id, $place)
    {
        // $organization = Organization::findOrFail($org_id);
        // $organization->spaces()->detach($space_id);

        // DB::beginTransaction();

        $place = Place::where('id', $place)->where('organization_id', $org_id)->delete();

        // DB::rollBack();

        if ($place) {
            return redirect('admin/organizations/' . $org_id.'/edit#places_tab')->with('message', 'Ubicación eliminada correctamente');
        } else {
            return redirect('admin/organizations/' . $org_id.'/edit#places_tab')->withErrors('Error al eliminar la ubicación');
        }

    }


    public function OrganizationsPlaces($termino = '')
    {
        
        $list_orgs = Organization::where('state', 1)->orderby('name', 'ASC')
        ->where('name', 'LIKE', "%$termino%")
        ->get();

        $list_places = [];
        foreach($list_orgs as $organization) {

            foreach($organization->places as $place) {

                $array_place['id'] = $place->id;
                $array_place['name'] = $organization->name." - ";
                if ($place->placeable_type == 'App\Space') {

                    $array_place['name'] .= $place->placeable->address->street->name." ".$place->placeable->address->number. ", ".$place->placeable->name;

                } else if ($place->placeable_type == 'App\Address') {
                    $array_place['name'] .= $place->placeable->street->name." ".$place->placeable->number;
                }

                array_push($list_places, $array_place);
            }
        }
        
        return $list_places;

    }

    public function OrganizationPlace($organization, $place)
    {
        
        // echo ('<pre>');print_r($place);echo ('</pre>'); exit();
        // $organization = Organization::findOrFail($organization);
        //tr
        $place = Place::findOrfail($place);

        if($place->placeable_type == 'App\\Space') {

            $place->placeable->address->street;

        } else {

            $place->placeable->street;
        }

        return $place;

    }


    public function OrganizationSpace( $id)
    {        
        $space =  Space::with('category')->with('address.zone')->with('file')->with('tagged')
        ->where('id',$id)
        ->first();
        return $space;

    }


    /*------------------------------------------------
    /*----------------------------------------------*/
    # Destacar / Impactar la organizacion en home o asides
    /*------------------------------------------------
    /*----------------------------------------------*/

    public function highLightHomeAside(Organization $organization)
    {

        if (!$organization->highlight_home_aside){
            $organization->highlight_home_aside = "home_aside";
        } else {
            $organization->highlight_home_aside = NULL;
        }
        $result = $organization->save();

        if ($result) {
            return back()->with('message', 'Organización actualizada correctamente');
        } else {
            return back()->withErrors('Error al actualizar organización');
        }

    }


    /*------------------------------------------------
    /*----------------------------------------------*/
    # Destacar / resaltar organizacion 
    /*------------------------------------------------
    /*----------------------------------------------*/

    public function highLight(Organization $organization)
    {

        if (!$organization->highlight){
            $organization->highlight = "emphasize";
        } else {
            $organization->highlight = NULL;
        }
        $result = $organization->save();

        if ($result) {
            return back()->with('message', 'Organización modificada correctamente');
        } else {
            return back()->withErrors('Error al actualizar organización');
        }

    }

}