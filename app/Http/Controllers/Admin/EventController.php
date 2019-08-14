<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Event;
use App\Category;
use App\Place;
use App\Calendar;

// Soporte para transacciones 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//Request
use App\Http\Requests\EventStoreRequest;


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
        $events = Event::orderBy('title', 'ASC')->paginate();

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // echo ("<pre>");print_r("hi");echo ("</pre>"); exit();

        // $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        // $places = Place::orderBy('name', 'ASC')->get();
        // $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request)
    {
        # Start transaction
        DB::beginTransaction();

        $event = Event::create($request->all());

        $tags = explode(',', $request->get('tags'));
        $event->tag($tags);

        foreach ($event->tags as $tag) {
           $tag->setGroup('Eventos');
        }

        $result = $event->update();

        // echo ("<pre>");print_r($event->toArray());echo ("</pre>"); exit();

        if ($result) {
            DB::commit();
            return redirect()->route('events.edit', $event->id)->with('message', 'Evento creado correctamente');
        } else {
            DB::rollBack();
            return back()->with('message', 'Evento creado correctamente');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $event = Event::with('calendars')->findOrFail($id);

        // echo ("<pre>");print_r($event);echo ("</pre>"); exit();

        #Manejo de Tags 
        // dd($event->tagNames());

        # Recuperar Tag agrupados bajo "Eventos", pertenecientes al evento puntual
        $group_type = 'App\Event';

        $tags_events = '';
        $array_tags_events = Tagged::join('tagging_tags','tagging_tags.slug','tagging_tagged.tag_slug')
        ->join('tagging_tag_groups','tagging_tags.tag_group_id','tagging_tag_groups.id')
        ->where('tagging_tagged.taggable_id', $id )
        ->where('tagging_tagged.taggable_type', $group_type )
        ->where('tagging_tag_groups.slug', 'eventos')
        ->select('tagging_tags.name')
        ->get()->toArray();

        foreach ($array_tags_events as $key => $tag) {
            $tags_events .= $tag ['name'].', ';
        }

        // Recuperar Tag NO AGRUPADOS 
        $tags_no_events = '';

        $array_tags_no_events = Tagged::join('tagging_tags','tagging_tags.slug','tagging_tagged.tag_slug')
        ->where('tagging_tagged.taggable_id', $id )
        ->where('tagging_tagged.taggable_type', $group_type)
        ->where('tagging_tags.tag_group_id','=',NULL)
        ->get()->toArray();

        foreach ($array_tags_no_events as $key => $tag) {
            $tags_no_events .= $tag ['name'].', ';
        }

        $group_events = Tag::inGroup('Eventos')->get()->toArray();

        $places = $zones = $addresses_types = $streets = $organizations = array();
        
        // $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();
        // $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();
        // $streets = $this->getStreets();
        // $organizations = Organization::where('state', 1)->orderBy('name')->with('addresses')->get();

        // $calendars = Calendar::orderBy('start_date', 'ASC')->get();

        $places = Place::orderBy('name', 'ASC')->get();

        return view('admin.events.edit', compact('event', 'group_events', 'tags_events', 'tags_no_events', 'places', 'calendars', 'zones', 'addresses_types', 'streets', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        // dd($request->get('tags_events'));
        # Start transaction
        DB::beginTransaction();

        $event = Event::findOrFail($id);

        #Determinar Tags a Asociar
        // Los tags categorizados bajo eventos se definen previamente, no pudiendose
        // agregar en forma dinámica desde el formulario de edición de eventos. 
        // Por mas que estos se cargen en el campo correspondiente a "categorias"
        // serán taggeados al evento pero se ubicaran en etiquetas asociadas
        $tags_events = explode(',', $request->get('tags_events'));

        // foreach ($event->tags as $tag) {
        //    $tag->setGroup('Eventos');
        // }

        $tags_no_events = explode(',', $request->get('tags_no_events'));
        //

        $tags =  array_merge($tags_events, $tags_no_events);

        $event->retag($tags);

        $result = $event->fill($request->all())->save();

        // echo ("<pre>");print_r($event->tagNames());echo ("</pre>"); exit();

        if ($result) {
            DB::commit();
            return redirect()->route('events.edit', $event->id)->with('message', 'Evento actualizado correctamente');
        } else {
            DB::rollBack();
            return back()->with('message', 'Error al actualizar el evento');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // echo ("<pre>");print_r("TO DO: eliminar evento");echo ("</pre>"); exit();
        $event = Event::findOrFail($id)->delete();
        return back()->with('message', 'Evento eliminado correctamente');
    }


    

    public function saveEventCalendar(Request $request)
    {

        # Start transaction
        // DB::beginTransaction();

        $data = [];

        $event_id = $request->get('event_id');

        $event = Event::findOrFail($event_id);

        $calendar_id = $request->get('calendar_id');

        if ($calendar_id == 0) {
            //Creo el calendario-funcion y si ok, paso los datos del nuevo calendar
            $calendar = Calendar::create($request->all());
            $result = ($calendar) ? TRUE : FALSE;
            $new = TRUE;
            
        } else {
            //Compruebo si existe...
            $calendar = Calendar::findOrFail($calendar_id);
            // Actualiza calendar
            $result = $calendar->fill($request->all())->save(); 
            $new = FALSE;
            
        }

        $data['result'] = $result;
        $data['calendar'] = $calendar;
        $data['calendar']['token'] =  csrf_token();
        $data['new'] = $new;

        // DB::rollBack();
        return $data;

    }


    
    /*===================================================
    Eliminar calendarios-funciones relacionadas a eventos
    ====================================================*/
    
    public function destroyEventCalendar($id_event, $id_calendar)
    {
        // DB::beginTransaction();
        $data = [];
        
        $data['result'] = Calendar::where('event_id',$id_event)->find($id_calendar)->delete();
        
        if ($data['result']) {
            $data['message'] = 'Calendario eliminado correctamente';
        } else {
            $data['message'] = 'Se produjo un error al eliminar el calendario';
        }
        // DB::rollBack();
        return $data;
        
    }
    
    // /*=============================================
    // Obtener HTML listado de funciones
    // =============================================*/

    // public function getHtmlEventFunction() {

    //      return view('admin.events.partials.event_calendar');

    // } //END method

}