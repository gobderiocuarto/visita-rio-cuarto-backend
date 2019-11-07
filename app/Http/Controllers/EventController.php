<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;
use App\Calendar;
// use App\Group;


use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");  

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get();


        # Listado de eventos
        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->whereNull('events.frame') //No mostrar marcos
        ->where('events.state', $state) //
        ->where('calendars.start_date', '>=', $today)
        ->select('events.*', 'calendars.start_date')
        ->orderBy('calendars.start_date', 'ASC')
        ->paginate();

        return view('web.events.index');
        
    }


    public function show($id, $slug)
    {
        # Detalle de evento
        $event = Event::with('place.organization')
        ->with('place.placeable')
        ->with('calendars')
        ->FindOrFail($id);

        # Listado de proximos eventos
        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");

        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->whereNull('events.frame') //No mostrar marcos
        ->where('events.state', $state)
        ->where('calendars.start_date', '>=', $today)
        ->select('events.*', 'calendars.start_date', 'calendars.start_time')
        ->orderBy('calendars.start_date', 'ASC')
        ->orderBy('calendars.start_time', 'ASC')
        ->limit(2)
        ->get();

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get();

        return view('web.events.show', compact('event', 'events', 'event_tags'));
    }



    public function getCategories($category_slug = NULL)
    {
        
        $actual_category = Tag::where('slug', $category_slug)->first();

        if(!$actual_category){
            abort(404);
        }
        
        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get();

        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");  

        # Listado de eventos
        $events = Event::withAnyTag([$category_slug])
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->whereNull('events.frame') //No mostrar marcos
        ->where('events.state', $state)
        ->where('calendars.start_date', '>=', $today)
        ->select('events.*', 'calendars.start_date', 'calendars.start_time')
        // ->distinct()
        ->orderBy('calendars.start_date', 'ASC')
        ->orderBy('calendars.start_time', 'ASC')
        ->paginate(8);

        return view('web.events.index', compact('events', 'event_tags'));       
    }

    public function getWhen($lapse = NULL)
    {
        
        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get();

        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");

        # Listado de eventos
        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->whereNull('events.frame') //No mostrar marcos
        ->where('events.state', $state)
        ->where('calendars.start_date', '>=', $today);

        switch ($lapse) {

            case 'hoy':

                $events = $events->where('calendars.start_date', $today);
                break;

            case 'maniana':

                $datetime_now = new \DateTime($today);
                $datetime = $datetime_now->modify('+1 day');
                $date = $datetime->format('Y-m-d');

                $events = $events->where('calendars.start_date', $date);
                break;

            case 'fin-de-semana':

                $next_saturday = date('Y-m-d', strtotime("saturday"));
                $next_sunday = date('Y-m-d', strtotime("sunday"));

                $events = $events->where('calendars.start_date', '>=',$today)
                ->whereBetween('calendars.start_date', [$next_saturday, $next_sunday]);
                break;

            case 'mes':

                $datetime_now = new \DateTime($today);

                $datetime_start = $datetime_now->modify('-1 day');
                $date_start = $datetime_start->format('Y-m-d');

                $datetime_end = $datetime_now->modify('last day of this month');
                $date_end_month = $datetime_end->format('Y-m-d');
                $events = $events->whereBetween('calendars.start_date', [$date_start, $date_end_month]);
                break;
            
            default:
                abort(404);
                break;
        }


        $events = $events->select('events.*', 'calendars.start_date', 'calendars.start_time')
        // ->distinct()
        ->orderBy('calendars.start_date', 'ASC')
        ->orderBy('calendars.start_time', 'ASC')
        ->paginate(8);

        return view('web.events.index', compact('events', 'event_tags')); 

    }

    public function getFrame($slug)
    {
        
        // $event_frame = Event::where('slug',$slug )->first();

        
        if(!$event_frame = Event::where('slug',$slug )->first()) {
            abort(404);
        }
        
        $today = date("Y-m-d");
        $state = 1;

        # Listado de eventos
        $events = Event::with('place.organization')
        ->join('event_group', 'events.group_id', 'event_group.group_id')
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->whereNull('events.frame') //No mostrar marcos;
        ->where('events.state', $state)
        ->orderBy('calendars.start_date', 'ASC')
        ->where('events.event_id', $event_frame->id)
        ->where('calendars.start_date', '>=', $today)
        ->select('events.*')
        ->distinct()
        ->paginate();

        // echo ('<pre>');print_r($events);echo ('</pre>'); exit();

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get()->toArray();

        return view('web.events.show_category', compact('events',  'event_tags'));

    }


    public function getWhere($where)
    {
        // echo ('<pre>');print_r('Donde');echo ('</pre>'); exit();

        $today = date("Y-m-d");
        $state = 1;

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get()->toArray();

        $events = Event::with('place.organization')
        ->join('event_group', 'events.group_id', 'event_group.group_id')
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->whereNull('events.frame') //No mostrar marcos;
        ->where('events.state', $state)
        ->where('calendars.start_date', '>=', $today)
        ->orderBy('calendars.start_date', 'ASC')
        ->join('places', 'places.id', 'events.place_id')
        ->join('organizations', 'organizations.id', 'places.organization_id')
        ->where('organizations.category_id', 27)
        ->select('events.*')
        ->distinct()
        ->paginate();

        // echo ('<pre>');print_r($events);echo ('</pre>'); exit();
        return view('web.events.show_category', compact('events',  'event_tags'));
    }

}
