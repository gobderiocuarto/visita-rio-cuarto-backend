<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Address;
use App\Organization;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request, Organization $organization)
    {
        $address = new Address();
        $address->organization_id = $organization->id;

        switch ($request->address_type) {
            case '1':
                $address_type_name = 'Dirección';
                break;
            case '2':
                $address_type_name = 'Casa Central';
                break;
            case '3':
                $address_type_name = 'Sucursal';
                break;

            case '4':
                $address_type_name = 'Oficina';
                break;

            case '5':
                $address_type_name = 'Planta Industrial';
                break;
            case '-1':
                $address_type_name = $request->address_type_name;
                break;
            
            default:
                $address_type_name = "";
                break;
        }

        $address->address_type = $address_type_name;
        $address->street = $request->get('street');
        $address->number = $request->get('number');
        $address->floor = $request->get('floor');
        $address->lat = $request->get('lat');
        $address->lng = $request->get('lng');
        $address->zone = $request->get('zone');

        $address->save();

        return redirect()->route('organizations.edit', $organization->id)->with('message', 'Lugar asociado exitosamente');
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
        //
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
        //
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
