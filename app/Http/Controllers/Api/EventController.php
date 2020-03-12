<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Calendar;
use App\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventCollection;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # coleccion general de eventos
        $events = $this->getBaseCollection();

        # Excluir Eventos Marco
        $events = $events->whereNull('events.frame'); 

        $events = $this->getQueries($request, $events);

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
    public function show($event_slug)
    {
        $event = Event::where('slug', $event_slug)->first();

        if (!$event) {
            abort(404);
        } 
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


    public function getEventsInFrame(Event $event_frame)
    {
                
        # Listado total de eventos activos
        $events = $this->getBaseCollection();

        # ID eventos marco / padre
        $events = $events->where('events.event_id', $event_frame->id);

        $events = $events->paginate(12);
        return EventResource::collection($events);

    }


    public function getEventsInTag(Request $request, $slug_tag = NULL)
    {
        
        $actual_tag = Tag::where('slug', $slug_tag)->first();

        if(!$actual_tag){
            abort(404);
        }

        $events = $this->getBaseCollection();

        # Excluir Eventos Marco
        $events = $events->whereNull('events.frame');

        # Seleccionar eventos con tag
        $events = $events->withAnyTag([$slug_tag]);

        // $events = $events->paginate(12);
        $events = $this->getQueries($request, $events);
        
        return EventResource::collection($events);

    }


    public function getEventsInCategory(Request $request, $category_slug)
    {

        $category = Category::where('slug', $category_slug)->first();

        if(!$category){
            abort(404);
        }

        // var_dump($category); exit();

        # Listado total de eventos activos
        $events = $this->getBaseCollection();

        $events = $events->join('places', 'places.id', 'events.place_id')
        ->join('organizations', 'organizations.id', 'places.organization_id')
        ->where('organizations.category_id', $category->id);

        # Excluir Eventos Marco
        $events = $events->whereNull('events.frame');

        // $events = $events->paginate(12);
        $events = $this->getQueries($request, $events);
        
        return EventResource::collection($events);


        // # Listado de eventos
        // $events = Event::join('calendars', 'calendars.event_id', 'events.id')
       
        // ->whereNull('events.frame') //No mostrar marcos        ->where('calendars.start_date', '>=', $today)
        

        // return view('web.events.index', compact('events', 'event_tags', 'title_index'));

    }



    


    /*--------------------------------------/*
    /*---------------------------------------
        Metodos privados
    /*---------------------------------------
    /*--------------------------------------*/


    private function getBaseCollection()
    {
        
        $group_id = 1; //GA visitariocuarto
        $state = 1; // Estado Activo
        
        # Listado total de eventos activos, pertenecientes a portal visitariocuarto
        $events = Event::join('calendars', 'calendars.event_id', 'events.id')
        ->join('event_group', 'events.id', 'event_group.event_id')
        ->where('event_group.group_id', $group_id)
        ->where('events.state', $state) // activos
        ->select('events.*');

        # Orden de presentación
        $events = $events->orderBy('calendars.start_date', 'DESC')
                         ->orderBy('calendars.start_time', 'ASC');

        return $events;
    }


    private function getQueries(Request $request, $events)
    {
        
        # Paginar registros, valor por defecto
        $paginate = 12; 

        # Almacenar los query a la url para mantenerlos en paginado de la consulta
        $query_filter = (object)[];
        
        # Filtrar por campo busqueda
        if (($request->search != '')) {
            $events = $events->where('events.title', 'like', '%'.$request->search.'%' );
            $query_filter->search = $request->search;
        }

        # Buscar por rango de fechas / calendarios
        if ($request->start_date) {
            
            if ($request->end_date) {

                $events = $events ->whereBetween('calendars.start_date', [$request->start_date, $request->end_date]);
                $query_filter->start_date = $request->start_date;
                $query_filter->end_date = $request->end_date;

            } else {

                $events = $events ->where('calendars.start_date', '>=', $request->start_date);
                $query_filter->start_date = $request->start_date;

            }

        }

        # Paginar registros
        if ($request->paginate != '') {

            $paginate = $request->paginate;
            $query_filter->paginate = $request->paginate;

        }

        $events = $events->paginate($paginate);

        # Agregar los query de la url al paginado de la consulta
        $events->appends((array)$query_filter);

        return $events;
    }
}
