<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventCollection;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // echo ('<pre>');print_r($request->all());echo ('</pre>'); exit();
        $group_id = 1; //GA
        $state = 1;
        // $today = date("Y-m-d");  
        $today = date("2019-03-06");  
        
        // $calendar = Calendar::where('start_date', '>=', $today)->get();
        // echo ('<pre>');print_r($calendar);echo ('</pre>'); exit();

        # Listado de eventos
        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->where('events.state', $state) // activos
        ->whereNull('events.frame') //No mostrar marcos
        ->where('calendars.start_date', '>=', $today);
        # Filtrar por campo busqueda
        if (($request->search != '')) {
            $events = $events->where('events.title', 'like', '%'.$request->search.'%' );
        }

        $events = $events->orderBy('calendars.start_date', 'DESC');
        $events = $events->select('events.*');

        $events = $events->paginate();
        
        // $events = $events->toSql();
        $events->appends(["today" => $today]);
        // dd($events);
        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return New EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
