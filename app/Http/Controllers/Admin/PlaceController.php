<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $places = Place::orderBy('id', 'DESC')->paginate();
        return view('admin.places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $array_data = array();
        $array_data ['streets'] = Street::orderBy('name', 'ASC')->get();
        $array_data ['zones'] = Zone::orderBy('name', 'ASC')->get();
        return view('admin.places.create', $array_data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(PlaceStoreRequest $request)
    {
        $place = Place::create($request->all());
        return redirect()->route('places.edit', $place->id)->with('message', 'Espacio creado con éxito');
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

        $array_data = array();
        $array_data ['place'] = Place::findOrFail($id);
        $array_data ['streets'] = Street::orderBy('name', 'ASC')->get();
        $array_data ['zones'] = Zone::orderBy('name', 'ASC')->get();

        return view('admin.places.edit', $array_data);
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
        $place = Place::findOrFail($id);

        $place->fill($request->all())->save();

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
