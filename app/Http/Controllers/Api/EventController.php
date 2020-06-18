<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Calendar;
use App\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventCollection;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;

class EventController extends Controller
{
    
    /*--------------------------------------/*
    /*---------------------------------------
        Eventos Marco
    /*---------------------------------------
    /*--------------------------------------*/

    public function indexFrames(Request $request)
    {
        # Datos generales de colecciones de eventos
        $events = $this->getBaseCollection();

        # Excluir Eventos Marco
        $events = $events->whereNotNull('events.frame');
        
        # Incluir opciones de filtrado
        $events = $this->getQueries($request, $events);

        return EventResource::collection($events);
      
    }


    public function showFrame($event_frame)
    {
        $event = Event::where('id', $event_frame)
        ->where('frame', "is-frame")
        ->first();

        if (!$event) {
            abort(404);
        } 
        return New EventResource($event);
    }



    public function showEventsInFrame(Event $event_frame, Request $request)
    {
                
        # Listado total de eventos activos
        $events = $this->getBaseCollection();

        # ID eventos marco / padre
        $events = $events->where('events.event_id', $event_frame->id);

        # Incluir opciones de filtrado
        $events = $this->getQueries($request, $events);

        return EventResource::collection($events);

    }


    /*--------------------------------------/*
    /*---------------------------------------
        Eventos NO Marco
    /*---------------------------------------
    /*--------------------------------------*/

    public function index(Request $request)
    {
        # Datos generales de colecciones de eventos
        $events = $this->getBaseCollection();

        # Excluir Eventos Marco
        $events = $events->whereNull('events.frame');
        
        # Incluir opciones de filtrado
        $events = $this->getQueries($request, $events);

        return EventResource::collection($events);
      
    }


    public function show(Event $event)
    {
        return New EventResource($event);
    }

    /*---------------------------------------
        END Eventos NO Marco
    ---------------------------------------*/




    /*--------------------------------------/*
    /*---------------------------------------
        Eventos asociados a un tag
    /*---------------------------------------
    /*--------------------------------------*/
    
    public function getEventsInTag(Request $request, $slug_tag = NULL)
    {
        
        $actual_tag = Tag::where('slug', $slug_tag)->first();

        if(!$actual_tag){
            abort(404);
        }

        $events = $this->getBaseCollection();

        # Excluir Eventos Marco
        $events = $events->whereNull('events.frame');

        # Seleccionar eventos asociados al tag
        $events = $events->withAnyTag([$slug_tag]);

        $events = $this->getQueries($request, $events);
        
        return EventResource::collection($events);

    }

    /*---------------------------------------
        END Eventos asociados a un tag
    ---------------------------------------*/


    /*-------------------------------------------------------/*
    /*-------------------------------------------------------
        Listar Eventos en base a la categoria a la que 
        pertenece su ubicación - organizacion
    /*-------------------------------------------------------
    /*------------------------------------------------------*/

    public function getEventsInCategory(Request $request, $category_slug)
    {

        $category = Category::where('slug', $category_slug)->first();

        if(!$category){
            abort(404);
        }

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

    }

    /*----------------------------------------------
        END Listar Eventos en base a la categoria
    ----------------------------------------------*/



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

    # Metodo para filtar listado de eventos segun diferentes criterios
    private function getQueries(Request $request, $events)
    {
        
        # Paginar registros, valor por defecto
        $paginate = 12; 
        
        # Obj para almacenar los query de la url para mantenerlos en paginado de la consulta
        $query_filter = (object)[];

        # Validar tipo de datos ingresados en el query
        $validator = Validator::make($request->all(), [
            'search'       => 'alpha_num',
            'end_date'     => 'date_format:Y-m-d',
            'start_date'   => 'required_with:end_date|date_format:Y-m-d',
            'paginate'     => 'numeric',
            'page'         => 'numeric',
        ]);

        if ($validator->fails()) {
            abort(404);
        }
        
         # Filtrar por campo termino de busqueda
         if (($request->search != '')) {
            $events = $events->where(function ($query) use ($request)  {
                            $query->where('events.title', 'like', '%'.$request->search.'%')
                            ->orWhere('events.slug', 'like', '%'.$request->search.'%')
                            ->orWhere('events.summary', 'like', '%'.$request->search.'%')
                            ->orWhere('events.description', 'like', '%'.$request->search.'%');
            });
            $query_filter->search = $request->search;
        }

        # Filtrar por rango de fechas / calendarios
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
