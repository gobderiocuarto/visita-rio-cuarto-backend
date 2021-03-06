<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\Zone;
use App\Organization;

use App\Event;
use App\Calendar;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;


class HomeController extends Controller
{
    
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
        ->where('events.state', $state)
        ->where('calendars.start_date', '>=', $today)
        ->select('events.*', 'calendars.start_date', 'calendars.start_time')
        ->orderBy('calendars.start_date', 'ASC')
        ->limit(8)
        ->get();

        return view('web.home.index', compact('events', 'event_tags'));
    }



    // public function search(Request $request)
    // {
        
    //     $query = $request->get('search');
    //     // $organizations = Organization::with('tagged')->where('name', 'like',"%$query%")->get();
    //     $organizations = Organization::search($query)->get();
        
    //     //dd($organizations);
    //     return view('web.home.index', compact('organizations'));
    // }

}
