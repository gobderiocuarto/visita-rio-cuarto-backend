<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Address;
use App\Organization;
use App\Place;

use \Conner\Tagging\Model\Tag;


class ApiController extends Controller
{
	//Traer datos de espacio por ej. para agregar a org
	public function getPlace($id)
    {
        $place =  Place::with('organizations', 'address.zone')->findOrFail($id);

        return $place;
    }


    public function getAddress($id)
    {

        $address =  Address::with('zone')->findOrFail($id);

        return $address;

        $streets = json_decode(file_get_contents('http://eventos.localhost/files/streets/streets.json'), true);

        $key = array_search($address->street_id, array_column($streets , 'id'));

        $address = $address->toArray();

        $address['street'] = $streets [$key];

        return $address;

        // $address =  Address::with('street', 'zone')->findOrFail($id);
        //return $address;
    }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Obtener detalle de un Lugar asociado a una organizacion 
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------

    public function getOrganizationPlace($organization, $place)
    {
        $organization = Organization::findOrFail($organization);
        
        // return $organization->places()->with('address.street')->where('places.id', $place )->first();
        return $organization->places()->with('address')->where('places.id', $place )->first();
    }




    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    // Obtener detalle de una dirección asociada a una organizacion 
    // -----------------------------------------------------------------
    // -----------------------------------------------------------------

    public function getAddressOrganization($organization, $address)
    {

        $organization = Organization::findOrFail($organization);
        //dd($organization);
        
        //   $address =  Address::with('street')
        //                ->join('address_organization', 'address_organization.address_id','addresses.id')
                    // ->where('addresses.id',$address)
                    // ->where('address_organization.organization_id',$organization)
                    // ->select('addresses.*', 'address_organization.address_type_id', 'address_organization.address_type_name' )
                    // ->first();

        // return $organization->addresses()->with('street')->where('addresses.id', $address)->first();
        return $organization->addresses()->where('addresses.id', $address)->first();
        
    }



    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener coleccción de nombres de tags asociados a grupo servicios
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getServiceTags($termino)
    {
        $service_tags = Tag::inGroup('Servicios')->where('name', 'LIKE', "%$termino%")->pluck('name');
        return $service_tags;
    }


    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags asociados a grupo servicios
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getServicesTags()
    {
        $service_tags = Tag::inGroup('Servicios')->pluck('name');
        return $service_tags;
    }

    
} # END Class