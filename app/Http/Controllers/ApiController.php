<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Address;
use App\Organization;
// use App\Space;
use App\Place;
use App\Space;
use App\Event;
use App\Calendar;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;


// Soporte para transacciones 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ApiController extends Controller
{
    # Traer datos de espacio por ej. para asociar a una ubicacion 
	public function getSpace($id)
    {
        $space =  Space::with('address.zone')->findOrFail($id);
        return $space;

    }


    // public function getAddress($id)
    // {

    //     $address =  Address::with('zone')->findOrFail($id);

    //     return $address;

    //     $streets = json_decode(file_get_contents('http://eventos.localhost/files/streets/streets.json'), true);

    //     $key = array_search($address->street_id, array_column($streets , 'id'));

    //     $address = $address->toArray();

    //     $address['street'] = $streets [$key];

    //     return $address;

    //     // $address =  Address::with('street', 'zone')->findOrFail($id);
    //     //return $address;
    // }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Obtener detalle de un Lugar asociado a una organizacion 
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------

    public function getOrganizationPlace($organization, $place)
    {
        
        // echo ('<pre>');print_r($place);echo ('</pre>'); exit();
        // $organization = Organization::findOrFail($organization);
        //tr
        $place = Place::findOrfail($place);

        if($place->placeable_type == 'App\\Space') {

            $place->placeable->address->street;

        } else {
            // echo ('<pre>');print_r("no place");echo ('</pre>'); exit();

            $place->placeable->street;
        }

        return $place;

        
        // return $organization->spaces()->with('address.street')->where('spaces.id', $space )->first();
        // return $organization->spaces()->with('address')->where('spaces.id', $space )->first();
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
    // Obtener coleccción de nombres de tags asociados a grupo "servicios",
    // filtrados por un termino
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getServiceTags($termino = '')
    {
        $service_tags = Tag::inGroup('Servicios')->where('name', 'LIKE', "%$termino%")->pluck('name');
        return $service_tags;
    }


    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags asociados a grupo servicios
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    // public function getServicesTags()
    // {
    //     $service_tags = Tag::inGroup('Servicios')->pluck('name');
    //     return $service_tags;
    // }


    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getEventsTags($termino = '')
    {
        $event_tags = Tag::inGroup('Eventos');
        $event_tags->where('name', 'LIKE', "%$termino%");
        $event_tags = $event_tags->pluck('name');
        return $event_tags;
    }


    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags NO asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getTags($termino = '')
    {
        $tags_no_events = Tag::where('tagging_tags.name', 'LIKE', "%$termino%")
        ->where( function($query){
            $query->where('tagging_tags.tag_group_id','!=',2)
            ->orWhereNull('tagging_tags.tag_group_id');
        })
        ->distinct()
        ->pluck('name');

        return $tags_no_events;
    }




    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------
    // Obtener datos de un calendario puntual, si pertenece a un evento dado
    // -------------------------------------------------------------------------
    // -------------------------------------------------------------------------

    public function getEventCalendar($id_event, $id_calendar)
    {

        // $event = Event::findOrFail((int)$id_event);

        $calendar = Calendar::where('event_id', (int)$id_event)->find((int)$id_calendar);

        // var_dump($calendar); exit();

        return $calendar; exit();

        // $text = $this->getHtmlEventFunction();
        // $data['html'] = html_entity_decode($text); 

    }

    
} # END Class