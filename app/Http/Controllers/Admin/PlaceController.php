<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


use App\Address;
use App\Place;
use App\Street;
use App\Zone;

use App\Http\Requests\PlaceStoreRequest;

class PlaceController extends Controller
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
        $places = Place::orderBy('id', 'ASC')->paginate();
        return view('admin.places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->get();
        return view('admin.places.create', compact ('streets', 'zones') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(PlaceStoreRequest $request)
    {

        //dd($request);

        # Start transaction
        DB::beginTransaction();

        $address = Address::create($request->all());
        
        // $address = Address::create ([
        //       'street_id' => $request->get('street') 
        //     , 'number' => $request->get('number')
        //     , 'floor' => $request->get('floor')
        //     , 'lat' => $request->get('lat')
        //     , 'lng' => $request->get('lng')
        //     , 'zone_id' => $request->get('zone')
        // ]);
        

        if (!$address) {

            DB::rollBack();

        } else {

            // $place = Place::create($request->all());
            $place = Place::create ([
                  'address_id' => $address->id
                , 'name' => $request->get('name') 
                , 'slug' => $request->get('slug')
                , 'description' => $request->get('description')
            ]);

            if (!$place) {
                DB::rollBack();
                return redirect()->back()->withErrors('Error al crear un espacio');

            } else {
                DB::commit();
                return redirect()->route('places.edit', $place->id)->with('message', 'Espacio creado con éxito');
            }
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

        $place = Place::findOrFail($id);
        $address = Address::where('id', $id)->first();
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->get();

        //dd($array_data);

        return view('admin.places.edit', compact('place','address','streets','zones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //dd($request);
        $place = Place::findOrFail($id);

        # Start transaction
        DB::beginTransaction();

        $place->name = $request->get('name'); 
        $place->slug = $request->get('slug');
        $place->description = $request->get('description');
    
        $result_1 = $place->save();

        if (!$result_1) {

            DB::rollBack();

        } else {

            $address = Address::where('id', $place->address_id)->update ([
                  'street_id' => $request->get('street_id') 
                , 'number' => $request->get('number')
                , 'floor' => $request->get('floor')
                , 'lat' => $request->get('lat')
                , 'lng' => $request->get('lng')
                , 'zone_id' => $request->get('zone_id')
            ]);


            if (!$address) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        }



        return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
