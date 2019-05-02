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
        $place =  Place::with('organizations', 'address.street', 'address.zone')->findOrFail($id);

        return $place;
    }



    public function getAddress($id)
    {
        $address =  Address::with('street', 'zone')->findOrFail($id);

        return $address;
    }



    public function getOrganizationPlace($organization, $place)
    {
        $place =  Place::with('address.street')
        			    ->join('organization_place', 'organization_place.place_id','places.id')
						->where('places.id',$place)
						->where('organization_place.organization_id',$organization)
						->select('places.*', 'organization_place.address_type_id', 'organization_place.address_type_name')
						->first();

        return $place;
    }


    public function getAddressOrganization($organization, $address)
    {

    	//dd($organization);
    	
        $address =  Address::with('street')
        			    ->join('address_organization', 'address_organization.address_id','addresses.id')
						->where('addresses.id',$address)
						->where('address_organization.organization_id',$organization)
						->select('addresses.*', 'address_organization.address_type_id', 'address_organization.address_type_name' )
						->first();

        return $address;
        
    }


    public function getServiceTags($termino)
    {
        $service_tags = Tag::inGroup('Servicios')->where('name', 'LIKE', "%$termino%")->pluck('name');
        return $service_tags;
    }

    
} # END Class
