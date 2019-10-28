<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\Address;
use App\Space;
use App\Organization;
use App\Place;
use App\Event;
use App\Calendar;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;


// Soporte para transacciones 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ApiController extends Controller
{
    
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Categorías
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    // api/categories/{id}

    // Detalle de categoría en base a su id
    // ----------------------------------------------------------------

    public function getCategory($id)
    {
        $category =  Category::with('category')->with('categories')
        ->where('id',$id)
        // ->first();
        ->get();

        return $category;

    }

    // ----------------------------------------------------------------
    // api/categories/{termino?}

    // Listado total de categorías o en base a término de búsqueda
    // ----------------------------------------------------------------

    public function getCategories($termino = '')
    {
        $list_categories = Category::with('category')->with('categories')
        ->where('state', 1)
        ->orderby('name', 'ASC')
        ->where('name', 'LIKE', "%$termino%")
        ->get();

        return $list_categories;

    }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Direcciones
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    // api/addresses/{id}

    // Detalle de direccion en base a su id
    // ----------------------------------------------------------------

    public function getAddress($id)
    {
        $address =  Address::with('zone')
        ->where('id',$id)
        // ->first();
        ->get();

        return $address;

    }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Espacios
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    // api/spaces/{id}

    // Detalle de espacio en base a su id
    // ----------------------------------------------------------------

	public function getSpace($id)
    {
        $space =  Space::with('category')->with('address.zone')->with('file')->with('tagged')
        ->where('id',$id)
        // ->first();
        ->get();
        return $space;

    }


    // ----------------------------------------------------------------
    // api/spaces/{termino?}

    // Listado total de espcios o en base a término de búsqueda
    // ----------------------------------------------------------------

    public function getSpaces($termino = '')
    {
        $list_spaces = Space::with('category')->with('address.zone')->with('file')->with('tagged')
        ->where('state', 1)
        ->orderby('spaces.name', 'ASC')
        ->where('name', 'LIKE', "%$termino%")
        ->get();

        return $list_spaces;

    }




    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Organizaciones
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    // api/organizations/{id}

    // Detalle de organizacion en base a su id
    // ----------------------------------------------------------------

    public function getOrganization($id)
    {
        $org = Organization::with('category')->with('tagged')->with('file')->with('places.placeable')
        ->where('id',$id)
        ->get();

        return $org;

    }


    // ----------------------------------------------------------------
    // api/organizations/{termino?}

    // Listado total de organizaciones o en base a término de búsqueda
    // ----------------------------------------------------------------

    public function getOrganizations($termino = '')
    {

        $list_orgs = Organization::with('category')->with('tagged')->with('file')->with('places.placeable')
        ->where('state', 1)
        ->orderby('organizations.name', 'ASC')
        ->where('name', 'LIKE', "%$termino%")
        ->get();

        return $list_orgs;

    }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Eventos
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    // api/events/{id}

    // Detalle de evento en base a su id
    // ----------------------------------------------------------------

    public function getEvent($id)
    {
        $event = Event::with('tagged')->with('event')->with('events')->with('place.organization')->with('calendars')->with('file')
        ->where('id',$id)
        ->get();

        return $event;

    }


    // ----------------------------------------------------------------
    // api/events/{termino?}

    // Listado total de eventos o en base a término de búsqueda
    // ----------------------------------------------------------------

    public function getEvents($termino = '')
    {

        $list_events = Event::with('tagged')->with('event')->with('events')->with('place.organization')->with('calendars')->with('file')
        ->where('state', 1)
        ->orderby('events.title', 'ASC')
        ->where('title', 'LIKE', "%$termino%")
        ->get();

        return $list_events;

    }


    // -------------------------------------------------------------------------
    // api/events/{event}/calendars/{calendar}

    // Obtener datos de un calendario puntual, si pertenece a un evento dado
    // -------------------------------------------------------------------------

    public function getEventCalendar($id_event, $id_calendar)
    {

        $calendar = Calendar::where('event_id', (int)$id_event)->find((int)$id_calendar);

        // var_dump($calendar); exit();
        return $calendar;

    }



    // ----------------------------------------------------------------
    // ----------------------------------------------------------------
    // Tags
    // ----------------------------------------------------------------
    // ----------------------------------------------------------------


    // ------------------------------------------------------------------------------
    // api/services/{termino?}

    // Obtener colección de nombres de tags agrupados bajo "SERVICIOS"
    // ------------------------------------------------------------------------------

    public function getServicesTags($termino = '')
    {
        $service_tags = Tag::inGroup('Servicios')
        ->where('name', 'LIKE', "%$termino%")->pluck('name');
        return $service_tags;
    }


    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------

    public function getEventsTags($termino = '')
    {
        
        $event_tags = Tag::inGroup('Eventos');
        $event_tags->where('name', 'LIKE', "%$termino%");
        $event_tags = $event_tags->pluck('name');
        return $event_tags;
    }


    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags NO asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------

    public function getNoEventsTags($termino = '')
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








    // ----------------------------------------------------------------
    // api/places/{termino?}
    // Mostrar listado de lugares y su organización asociada
    // En base al nombre de esta.

    // Usado en la edición de un evento, para asociarle un lugar
    // mediante el uso de un modal de busqueda
    // ----------------------------------------------------------------

    public function getPlacesOrganizations($termino = '')
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

            $place->placeable->street;
        }

        return $place;

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



    


    




    

    
} # END Class