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
use App\Place;
use App\Street;
use App\Zone;

use \Conner\Tagging\Model\Tag;

use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Requests\OrganizationUpdateRequest;

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
    public function index()
    {
        $organizations = Organization::orderBy('id', 'ASC')->paginate();
        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        $places = Place::orderBy('name', 'ASC')->get();
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        return view('admin.organizations.create', compact('categories','places','streets', 'zones') );
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

        /*

        $organization = Organization::create ([
             'category_id' => $request->get('category_id')
            , 'name' => $request->get('name') 
            , 'slug' => $request->get('slug')
            , 'description' => $request->get('description')
            , 'email' => $request->get('email')
            , 'phone' => $request->get('phone')
            , 'web' => $request->get('web')
        ]);
        */

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
    public function show($id)
    {
        //
    }

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
        $places = Place::orderBy('name', 'ASC')->get();
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();

        return view('admin.organizations.edit', compact('organization','tags','categories','places','streets', 'zones', 'addresses_types') );
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
        $organization->fill($request->all())->save();

        $tags = explode(',', $request->get('tags'));
        $organization->retag($tags);

        foreach ($organization->tags as $tag) {
           $tag->setGroup('Servicios');
        }

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

        //dd($request);

        $organization = Organization::with('addresses', 'places')->findOrFail($org_id); 

        $address_type_id = $request->get('address_type');

        // Determino el nombre del tipo de dirección
        if ($address_type_id === "0"){

            //dd('nuevo');

            $address_type_name =  $request->get('address_type_name');

        } else {

            $address_type_name = AddressType::findOrFail($address_type_id)->name;
            
        }


        # Start transaction
        DB::beginTransaction();

        // Elimino relacion anterior de espacio / dirección
        if ($request->get('prev_rel_type') === "place"){

            $organization->places()->detach($request->get('prev_rel_value'));

        } else if ($request->get('prev_rel_type') === "address"){

            $organization->addresses()->detach($request->get('prev_rel_value'));

        }


        if($request->place) { // Si hay espacio asociado a la org

            // Determinar si el espacio ya esta asociado a la org
            $exist = $organization->places()->where('places.id', $request->place)->first();

            if(empty($exist)) {

                $organization->places()->attach($request->place, ['address_type_name' => $address_type_name, 'address_type_id' => $address_type_id ]);

                // $this->setStorageResponse('place', $request->place);


                // DB::rollBack();
                DB::commit();

                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')
                        ->with('message', 'Espacio asociado con éxito')
                        ->with('action', ['type' => 'place', 'value' => $request->place ]);

            } else {

                DB::rollBack();

                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->withErrors('El espacio elegido ya se encuentra asociado');

            }

        } else {

            //dd($request);

            $address = Address::create($request->all());
            
            if ($address) {
                
                $address_organization = $address->organizations()->attach($organization->id, ['address_type_name' => $address_type_name, 'address_type_id' => $address_type_id ]);


                // DB::rollBack();
                DB::commit();


                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')
                                ->with('message', 'Dirección asociada con éxito')
                                ->with('action', ['type' => 'address', 'value' => $address->id ]);

            } else {

                DB::rollBack();

                return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->withErrors('Error al crear la dirección');
            }

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

        return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->with('message', 'Dirección eliminada correctamente');
    }


    public function destroyPlace($org_id, $place_id)
    {
        $organization = Organization::findOrFail($org_id);
        $organization->places()->detach($place_id);

        return redirect('admin/organizations/' . $organization->id.'/edit#places_tab')->with('message', 'Espacio desvinculado correctamente');
    }

}