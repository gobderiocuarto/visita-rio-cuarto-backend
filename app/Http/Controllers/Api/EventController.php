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
        
        $group_id = 1; //GA
        $state = 1; // Estado Activo
        // $today = date("Y-m-d");  
        
        # Listado total de eventos no marco activos, pertenecientes a portal visita
        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->where('events.state', $state) // activos
        ->whereNull('events.frame')  //No mostrar marcos
        ->select('events.*');

        # Orden de presentación
        $events = $events->orderBy('calendars.start_date', 'DESC');
        
        # Filtrar por campo busqueda
        if (($request->search != '')) {
            $events = $events->where('events.title', 'like', '%'.$request->search.'%' );
        }

        # Buscar por rango de fechas / calendarios
        if ($request->start_date) {

            if ($request->end_date) {

                $events = $events ->whereBetween('calendars.start_date', [$request->start_date, $request->end_date]);

            } else {

                $events = $events ->where('calendars.start_date', '>=', $request->start_date);

            }

        } else {
            // echo ('<pre>');print_r("date no def");echo ('</pre>'); exit();
        }
        
        // $events->appends(["today" => $today]);
        $events = $events->paginate(2);
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
