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

        $calendars = Calendar::where('start_date','>=',$today)
        ->distinct()
        ->pluck ('event_id');

        echo ("<pre>");print_r($calendars->toArray());echo ("</pre>"); exit();


        // $calendars = Calendar::where('start_date','>=',$today)
        // ->orderBy('start_date', 'ASC')
        // ->distinct()
        // ->pluck ('event_id');

        // // echo ("<pre>");print_r($calendars->toArray());echo ("</pre>"); exit();

        // $events = Event::with('place.organization')->with('calendars')
        // ->where('events.state', $state)
        // ->whereNull('events.frame') //No mostrar marcos
        // ->whereIn('id', $calendars)
        // ->orderBy('calendars.start_date', 'ASC')
        // ->limit(5)
        // ->get();



        // # Listado de eventos
        // $events = Event::with('place.organization')->with('calendarsToday')
        // ->where('events.state', $state)
        // ->whereNull('events.frame') //No mostrar marcos
        // ->whereIn('id', $calendars)
        // // ->Join('event_group', 'events.group_id', 'event_group.group_id')
        // // ->join('calendars', 'calendars.event_id', 'events.id')
        // // ->where('calendars.start_date', '>=', $today)
        // // ->orderBy('calendars.start_date', 'DESC')
        // ->select('events.*')
        // // ->distinct()
        // // ->limit()
        // ->paginate(1);
        // // ->get(1);

        // echo ("<pre>");print_r($events);echo ("</pre>"); exit();
        
        // $events = $events->join('calendars', 'calendars.event_id', 'events.id')
        // // ->join('event_group', 'event_group.group_id', 3)
        // // ->where('event_group.group_id', $group_id)
        // ->where('calendars.start_date', '>=', $today)
        // ->whereNull('events.frame') //No mostrar marcos
        
        // echo ("<pre>");print_r($events);echo ("</pre>"); exit();
        // debe existir al menos un calendario
        // return view('web.events.index', compact('events', 'event_tags'));
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
        
        $events = Event::with('place.organization')->with('calendars')
        ->where('events.state', $state)
        ->whereNull('events.frame') //No mostrar marcos
        ->join('event_group', 'events.group_id', 'event_group.group_id')
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->where('calendars.start_date', '>=', $today)
        ->orderBy('calendars.start_date', 'ASC')
        ->select('events.*')
        ->distinct()
        ->limit(4)
        ->get();

        // echo ("<pre>");print_r($events);echo ("</pre>"); exit();

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get();

        return view('web.events.show', compact('event', 'events', 'event_tags'));
    }



    public function getCategories($category_slug = NULL)
    {
        
        echo ("<pre>");print_r("to do listar por categoria");echo ("</pre>"); exit();
        

        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");  

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get()->toArray();

        # Listado de eventos
        $events = Event::with('place.organization')
        ->whereNull('events.frame') //No mostrar marcos;
        ->where('events.state', $state);

        if ($category_slug) {

            $events = $events->withAnyTag([$category_slug]);
            $actual_category = Tag::where('slug', $category_slug)->first();

        }

        $events = $events
        ->join('event_group', 'events.group_id', 'event_group.group_id')
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->where('calendars.start_date', '>=', $today)
        ->orderBy('calendars.start_date', 'DESC')
        ->select('events.*')
        ->distinct()
        ->paginate(1);

        return view('web.events.show_category', compact('events',  'event_tags', 'actual_category'));

    }

    public function getWhen($lapse = NULL)
    {
        
        $group_id = 1; //GA
        $state = 1;
        $today = date("Y-m-d");  
        // $today = "2019-01-01"; 
        
        // echo ('<pre>');print_r($today);echo ('</pre>'); exit();

        # Total de tags / categorias eventos para mostrar en nav
        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get()->toArray();

        // # Listado de eventos
        $events = Event::with('place.organization')
        ->whereNull('events.frame') //No mostrar marcos;
        ->where('events.state', $state)
        ->join('event_group', 'events.group_id', 'event_group.group_id')
        ->join('calendars', 'calendars.event_id', 'events.id')
        ->orderBy('calendars.start_date', 'ASC');

        switch ($lapse) {
            case 'hoy':

                $events = $events->where('calendars.start_date', $today);
                break;

            case 'manana':

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

        $events = $events->select('events.*')
        ->distinct()
        ->paginate(1);

        // echo ('<pre>');print_r($events);echo ('</pre>'); exit();

        return view('web.events.show_category', compact('events',  'event_tags'));

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
