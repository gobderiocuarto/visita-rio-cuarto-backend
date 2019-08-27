<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Soporte para transacciones 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Address;
use App\AddressType;
use App\Category;
use App\Organization;
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

        $this->middleware('auth');

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

        //dd($request->all());

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



    public function storePlace(Request $request, $org_id)
    {

        // dd($request->all());
        $organization = Organization::with('addresses', 'spaces')->findOrFail($org_id);
        
        //dd($request->get('address_type_name'));

        $address_type_id = $request->get('address_type');

        // Determino el nombre del tipo de dirección
        if ($request->get('address_type') === "0"){ // nuevo tipo de ubicación

            $address_type_name =  $request->get('address_type_name');

        } else {
            // Selecciona el tipo de ubicación del listado de la db
            $address_type_name = AddressType::findOrFail($address_type_id)->name;
            
        }

        # Start transaction
        // DB::beginTransaction();

        if($request->space) { // asocia space a org

            // Determinar si el espacio ya esta asociado a la org
            $exist = $organization->spaces()->where('organizationable_id', $request->space)
            ->where('organizationable_type', 'App\Space')->first();

            if(!empty($exist)) {

                //dd($exist);
                // DB::rollBack();
                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->withErrors('La ubicación elegida ya se encuentra asociada');

            } else {

                // Elimino relacion anterior de ubicación
                if ($request->get('prev_rel_type') === "space"){

                    $organization->spaces()->detach($request->get('prev_rel_value'));

                } else if ($request->get('prev_rel_type') === "address"){

                    $organization->addresses()->detach($request->get('prev_rel_value'));
                    Address::find($request->get('prev_rel_value'))->delete();


                }

                $organization->spaces()->attach($request->space, ['address_type_name' => $address_type_name, 'address_type_id' => $request->get('address_type')]);

                // $this->setStorageResponse('space', $request->space);
                // DB::commit();

                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')
                        ->with('message', 'Se actualizó correctamente la ubicación')
                        ->with('action', ['type' => 'space', 'value' => $request->space ]);

            }

        } else { // asocia address a org

            //dd($request);

            // Si existe, elimino relacion con ubicación anterior
            if ($request->get('prev_rel_type') === "space"){

                $organization->spaces()->detach($request->get('prev_rel_value'));
                $address = Address::create($request->all());
               

            } else if ($request->get('prev_rel_type') === "address"){

                $organization->addresses()->detach($request->get('prev_rel_value'));
                $address = Address::find($request->get('prev_rel_value'));
                $address->fill($request->all())->save();
                // dd($address);

            } else {

                $address = Address::create($request->all());

            }

            $organization->addresses()->attach($address->id, ['address_type_name' => $address_type_name, 'address_type_id' => $request->get('address_type')]);
            
            // DB::commit();
            return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')
                                ->with('message', 'Ubicación asociada con éxito')
                                ->with('action', ['type' => 'address', 'value' => $address->id ]);

            // } else {

            //     DB::rollBack();

            //     return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->withErrors('Error al crear la dirección');
            // }

        }

    }



    public function destroyAddress($org_id, $address_id)
    {
        
        $organization = Organization::findOrFail($org_id);
        $organization->addresses()->where('addresses.id', $address_id)->delete();

        // $address = Address::join('address_organization', 'addresses.id', '=', 'address_organization.address_id')
        //     ->where('address_organization.organization_id', $org_id)
        //     ->where('address_organization.address_id', $address_id)
        //     ->delete();

        return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->with('message', 'Ubicación eliminada correctamente');
    }


    public function destroySpace($org_id, $space_id)
    {
        $organization = Organization::findOrFail($org_id);
        $organization->spaces()->detach($space_id);

        return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->with('message', 'Ubicación desvinculada correctamente');
    }

}