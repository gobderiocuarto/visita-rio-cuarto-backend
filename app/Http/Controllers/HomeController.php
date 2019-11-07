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
        $events = Event::get();
        
        // where('events.state', $state)
        // // ->whereNull('events.frame') //No mostrar marcos
        // ->join('event_group', 'events.group_id', 'event_group.group_id')
        // ->join('calendars', 'calendars.event_id', 'events.id')
        // ->where('calendars.start_date', '>=', $today)
        // ->orderBy('calendars.start_date', 'DESC')
        // ->distinct()
        // ->select('events.*', 'calendars.start_date')
        // ->limit(4)
        // ->get();

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
