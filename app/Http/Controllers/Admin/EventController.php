<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;

use Caffeinated\Shinobi\Models\Role;

use Session;

use App\Event;
use App\Category;
use App\Group;
use App\Space;
use App\Place;
use App\Address;
use App\Calendar;
use App\Zone;
use App\Organization;

# Soporte para transacciones
use Illuminate\Support\Facades\DB;

# Autentificacion de usuarios
use Illuminate\Support\Facades\Auth;

//Request
use App\Http\Requests\EventStoreRequest;

# Imagenes
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use App\Traits\ImageTrait;


use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;

class EventController extends Controller
{
    use ImageTrait;

    
    public function __construct() {

        $this->large_width     = 1200 ; 
        $this->medium_width    = 700;
        $this->small_width     = 200; 

        $this->folder_img     = 'events/';        


        # Ver si el usuario autenticado puede cambiar el grupo del evento creado
        $this->middleware('event.edit-group', ['only' => ['store', 'update']]);

        # Chequear que el usuario este autorizado a crear eventos marco
        $this->middleware('event.create-frame', ['only' => ['store', 'update']]);

        # Chequear que el usuario puede cambiar el estado del evento
        $this->middleware('event.publish', ['only' => ['update']]);

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filter = (object)[];

        $appends = array();

        $event_tags = Tag::inGroup('Eventos')->orderBy('name', 'ASC')->get()->toArray();

        # Listado de eventos
        $events = Event::orderBy('created_at', 'DESC');

        # Si el usuario no es webmaster
        # mostramos todos sus eventos mas 
        # solamente los eventos publicados de otros grupos
        // if (!Auth::user()->can('all-access')){

        //     $events = $events->where('events.group_id', Auth::user()->group->id)
        //             ->orWhere(function($query){
        //                 $query->where('events.group_id','<>', Auth::user()->group->id)
        //                 ->where('state', 1);   
        //             });
        // } 
        
        # Filtrar por campo busqueda
        $filter->search = '';
        if (($request->search != '')) {

            $events = $events->where('events.title', 'like', '%'.$request->search.'%' );
            $filter->search = $request->search;
            $appends ['search'] = $request->search;
        }

        # Filtrar por categorias (Tags)
        $filter->category = "";
        if ($request->category != "" ) {
            $events = $events->withAnyTag([$request->category]);
            $filter->category = $request->category;
            $appends ['category'] = $request->category;
        }

        $events = $events->paginate(10);


        $events->appends((array)$filter);

        # Eventos NO PROPIOS asociados al grupo al que pertenece el usuario actual
        $events_in_group = Auth::user()->group->events()
        ->where('events.group_id', '<>', Auth::user()->group->id)
        ->pluck('events.id')
        // ->get()
        ->toArray();

        # Almacenar el query del listado actual para retornar 
        # a los ultimos parametros de busqueda, categoria, pagina desde la edición
        Session::flash('redirect',$request->getQueryString());

        return view('admin.events.index', compact('filter', 'events', 'event_tags', 'events_in_group'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $today = date("Y-m-d");
        
        $group = Auth::user()->group;

        $list_groups = Group::where('state',1)->get();

        // $frame_events = Event::where('state', 1)
        // ->where('frame', 'is-frame')->get();

        $frame_events = Event::join('calendars', 'calendars.event_id', 'events.id')
                        ->where('events.state', 1)
                        ->where('events.frame', 'is-frame')
                        ->where('calendars.end_date', '>=', $today)
                        ->select('events.*')
                        ->get();

        // echo ('<pre>');print_r($frame_events->toArray());echo ('</pre>'); exit();

        Session::keep(['redirect']);

        return view('admin.events.create', compact('group','list_groups', 'frame_events'));
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
        // DB::beginTransaction();

        if ($request->rel_frame) { 

            if ($request->rel_frame == 'is-frame') { # Definido como evento marco

                # Definir el evento como marco (el valor de event_id / padre será null)
                $request->request->add(['frame' => 'is-frame' ]);

            } else { # No definido como evento marco

                $event_frame = Event::where('id', $request->rel_frame )->where('frame', 'is-frame')->first();

                if ($event_frame) {
                    $request->request->add(['event_id' => $request->rel_frame ]);
                }
            }
        }

        Session::keep(['redirect']);

        # Recuperamos el usuario autenticado y lo agregamos al request
        $request->request->add(['user_id' => Auth::user()->id ]); 
        
        
        $event = Event::create($request->all());
        
        if ($event) {

            # Agregamos al evento a la relacion eventos - grupo (portal)
            // Auth::user()->group->events()->attach($event);
            # El middleware ya verifico el grupo asignado
            $event->groups()->attach($request->group_id);
            
            return redirect()->route('events.edit', $event->id)->with('message', 'Evento creado correctamente');
        } else {
            return back()->withErrors('Error al crear el evento, por favor intente nuevamente');
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
        $event = Event::findOrFail($id);

        # Tag categorizados bajo grupo "Eventos", asociados al evento puntual
        $tags_category_event = ''; // mostrar categorias como etiquetas separadas por coma

        # Tag no categorizados bajo grupo "Eventos", asociados al evento puntual
        $tags_no_category_event = '';

        foreach ($event->tags as $tag) {

            if ($tag) {
                if ($tag->isInGroup('Eventos')) {
                    $tags_category_event .= $tag ['name'].', ';
                } else {
                    $tags_no_category_event .= $tag ['name'].', ';
                }
            }

        }

        # Si se trata de un evento marco
        if ( (isset($event->frame)) && ($event->frame == 'is-frame')) {
            $calendar = [];
            if ($event->calendars->first()) {
                $calendar = $event->calendars->first();
            }
            
            $data = compact('event', 'tags_category_event', 'tags_no_category_event', 'calendar');

        } else {

            $frame_event = Event::where('id', $event->event_id)
            ->first();

            # Determinar lugar / ubicación actual del evento
            $actual_place_id = '';
            $actual_place = '';
            if ($event->place_id) {

                $actual_place_id = $event->place_id;
                $place = Place::with('organization')->find($event->place_id);
                $actual_place = $place->organization->name.' - ';

                if ($place->placeable_type == 'App\Space') {
                    $actual_place .=  $place->placeable->address->street->name.' '.$place->placeable->address->number.', '.$place->placeable->name;
                } else if ($place->placeable_type == 'App\Address') {
                    $actual_place .=  $place->placeable->street->name.' '.$place->placeable->number;
                }
            }

            $data = compact('event', 'frame_event', 'tags_category_event', 'tags_no_category_event', 'actual_place');
        }
        return view('admin.events.show', $data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $today = date("Y-m-d");
        
        $event = Event::findOrFail($id);

        $list_groups = Group::where('state',1)->get();

        # Todos los tags agrupados en categoria eventos, para mostrar en el select
        $tags_category = Tag::inGroup('Eventos')->get()->toArray();

        # Tag asociados al evento puntual... 
        $tags_category_event = []; // categorizados bajo grupo "Eventos"
        
        $tags_no_category_event = ''; # no categorizados bajo grupo "Eventos"

        foreach ($event->tags as $tag) {

            if ($tag) {

                if ($tag->isInGroup('Eventos')) {
                    $tags_category_event[] = $tag ['name'];
                } else {
                    $tags_no_category_event .= $tag ['name'].', ';
                }
            }
        }

        # Listado total de eventos marco habilitados

        # Listado total de organizaciones (y sus lugares) habilitadas
        // $list_orgs = Organization::where('state', 1)->orderby('name', 'ASC')->get();
        $list_orgs = array();

        # Si se trata de un evento marco
        if ( (isset($event->frame)) && ($event->frame == 'is-frame')) {
            $calendar = [];
            if ($event->calendars->first()) {
                $calendar = $event->calendars->first();
            }
            
            $data = compact('event','list_groups', 'tags_category', 'tags_category_event', 'tags_no_category_event', 'calendar');

        } else {

            // $frame_events = Event::where('state', 1)->where('frame', 'is-frame')->get();

            $frame_events = Event::join('calendars', 'calendars.event_id', 'events.id')
                        ->where('events.state', 1)
                        ->where('events.frame', 'is-frame')
                        ->where('calendars.end_date', '>=', $today)
                        ->select('events.*')
                        ->get();



            # Determinar lugar / ubicación actual del evento
            $actual_place_id = '';
            $actual_place = '';
            if ($event->place_id) {

                $actual_place_id = $event->place_id;
                $place = Place::with('organization')->find($event->place_id);
                $actual_place = $place->organization->name.' - ';

                if ($place->placeable_type == 'App\Space') {
                    $actual_place .=  $place->placeable->address->street->name.' '.$place->placeable->address->number.', '.$place->placeable->name;
                } else if ($place->placeable_type == 'App\Address') {
                    $actual_place .=  $place->placeable->street->name.' '.$place->placeable->number;
                }
            }

            $data = compact('event', 'list_groups', 'list_orgs', 'actual_place_id', 'actual_place',  'tags_category', 'tags_category_event', 'tags_no_category_event', 'frame_events');
            
        }

        Session::keep(['redirect']);

        return view('admin.events.edit', $data);

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
        // dd($request->all());
        # Start transaction
        DB::beginTransaction();

        $event = Event::findOrFail($id);

        # Determinar Tags asociados

        # Tags manejados como categorias de eventos
        // Los tags categorizados bajo eventos se definen previamente, no pudiendose
        // agregar en forma dinámica desde el formulario de edición de eventos. 
        // Por mas que estos se cargen en el campo correspondiente a "categorias"
        // serán taggeados al evento pero se ubicaran en etiquetas asociadas
        // $tags_category_event = explode(',', $request->get('tags_category_event'));

        $tags_category_event = $request->get('select_mult');

        if (!$tags_category_event) {
            $tags_category_event = [];
        }

        # Tags no categorizados
        $tags_no_category_event = explode(',', $request->get('tags_no_category_event'));

        # Unir tags categorizados y no categorizados, almacenar 
        $tags =  array_merge($tags_category_event, $tags_no_category_event);
        $event->retag($tags);

        # Actualizar evento
        $update_fields = [
            'group_id' => $request->get('group_id'),
            'title'=> $request->get('title'),
            'slug'=> $request->get('slug'),
            'organizer'=> $request->get('organizer'),
            'summary'=> $request->get('summary'),
            'description'=> $request->get('description'),
            'state'=> $request->get('state')
        ];


        # Si se trata de un evento marco
        if ( (isset($event->frame)) && ($event->frame == 'is-frame')) {

            $calendars_fields = [
                'event_id' => $id,
                'start_date'=> $request->get('start_date'),
                'start_time'=> $request->get('start_time'),
                'end_date'=> $request->get('end_date'),
                'end_time'=> $request->get('end_time'),
                'state'=> 1
            ];

            $calendar = $event->calendars->first();
            
            if ($calendar) {
                // Actualiza calendario
                $result = $calendar->fill($calendars_fields)->save(); 
                
            } else {
                //Creo el calendario
                $calendar = Calendar::create($calendars_fields);
                $result = ($calendar) ? TRUE : FALSE;
            }


        } else { # Si se trata de un evento común

            # Asignar a Evento marco
            $frame = null;
            if ($request->rel_frame) {

                $event_frame = Event::where('id', $request->rel_frame)
                ->first();
                
                if ($event_frame) {

                    $frame = $event_frame->id;

                }
            } 
            $update_fields = array_merge($update_fields, ['event_id'=> $frame ]);
            # END Asignar a Evento marco

            # Asignar Lugar / espacio
            $place = $request->get('place_id');
            $update_fields = array_merge($update_fields, ['place_id'=> $place]);

        }

        # Relacion eventos - grupo (portal)
        # El grupo propietario del evento tambien se vincula en la relacional
        # Agregar group_id "nuevo", quitar anterior
        # sin pisar otras relaciones existentes
        $event->groups()->detach($event->group_id);
        $event->groups()->attach($request->group_id);
        
        $result = $event->update($update_fields);

        # END actualizar evento

        // Session::keep(['redirect']);

        if ($result) {
            DB::commit();
            return back()->with('message', 'Evento actualizado correctamente');

            // return redirect()->route('events.index', Session::get('redirect'))->with('message', 'Evento actualizado correctamente');
            
        } else {
            DB::rollBack();
            return back()->withErrors('Error al actualizar el evento');
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
        $event = Event::findOrFail($id)->delete();

        return back()->with('message', 'Evento eliminado correctamente');
    }


    /*=============================================
    Asociar evento externo a grupo - portal propio
    =============================================*/

    public function associate($id)
    {
        $event = Event::findOrFail($id);

        # Grupo al que pertecece el usuario logueado
        $group = Auth::user()->group;

        $result = $group->events()->attach($event->id);
        // $result = $event->groups()->detach($event->group_id);


        $message = "Evento asociado correctamente";
        return back()->with('message', $message);


        // # Grupo al que pertecece el usuario logueado
        // $group = Auth::user()->group;

        // $group->events()->toggle($event->id);

        // # Ver si el evento esta asociado al grupo
        // $exist = $event->groups()
        // ->wherePivot('event_id', $event->id)
        // ->wherePivot('group_id', $group->id)
        // ->first();

        // // echo ('<pre>');var_dump($exist);echo ('</pre>'); exit();


        // if($exist) {

        //     $event->groups()->detach($event->group_id);
            
        //     // $result = $group->events()->detach($event);
        //     $message = "Evento desvinculado correctamente";
            
        // } else {
            
        //     $event->groups()->attach($event->group_id);
        //     // $result = $group->events()->attach($event);
        //     $message = "Evento asociado correctamente";
        // }

        // return back()->with('message', $message);
    }


    /*=============================================
    Asociar evento externo a grupo - portal propio
    =============================================*/

    public function dissociate($id)
    {
        $event = Event::findOrFail($id);


        # Grupo al que pertecece el usuario logueado
        $group = Auth::user()->group;

        $result = $group->events()->detach($event->id);
        // $result = $event->groups()->detach($event->group_id);

        $message = "Evento desvinculado correctamente";
        return back()->with('message', $message);

        // $group->events()->toggle($event->id);

        // # Ver si el evento esta asociado al grupo
        // $exist = $event->groups()
        // ->wherePivot('event_id', $event->id)
        // ->wherePivot('group_id', $group->id)
        // ->first();

        // // echo ('<pre>');var_dump($exist);echo ('</pre>'); exit();


        // if($exist) {

        //     $event->groups()->detach($event->group_id);
            
        //     // $result = $group->events()->detach($event);
        //     $message = "Evento desvinculado correctamente";
            
        // } else {
            
        //     $event->groups()->attach($event->group_id);
        //     // $result = $group->events()->attach($event);
        //     $message = "Evento asociado correctamente";
        // }

    }



    /*=============================================
    Cargar imagen de evento
    =============================================*/

    public function loadImageEvent(Request $request, $id) {

        # Start transaction
        // DB::beginTransaction();
        
        $event = Event::findOrFail($id);

        // echo ('<pre>');print_r($_FILES['file']);echo ('</pre>'); exit();

        if ($_FILES['file']['name'] != '') {

            if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $request->file('file'));

                if (!in_array($mime, $this->file_types)) {
                    return back()->withErrors('Tipo de archivo no permitido. (Sólo se permiten archivos JPG, PNG, GIF)');
                }
                
                # Generar el nombre final del archivo (metodo en Controller.php)
                $new_file_name = $this->renameFile($request->file('file'));
                

                #------------------------------
                # Almacenar archivos
                #------------------------------

                $result = $this->loadImages($request, $new_file_name);
                
                if(!$result) {
                    // DB::rollBack();
                    return back()->withErrors('Se produjo algún error al actualizar la imagen. Revise los cambios realizados');
                
                } else {

                    #----------------------------------------
                    # Borrar archivos anteriores, si existen
                    #----------------------------------------

                    if($event->file) {
                        # Borrar archivos
                        $result_del = $this->deleteImages($event->file->file_path);

                        # Borrar en DB
                        $delete_file = $event->file->delete();
                        
                    }

                    # Almacenar nueva imagen en DB
                    $result = $event->file()->create(['file_path'=> $new_file_name, 'file_alt'=> $request->get('file_alt') ]);
                    // $files = Storage::allFiles($folder_img);
                    // echo ('<pre>');print_r($files);echo ('</pre>'); exit();


                }                

            } else {

                return back()->withErrors('Error al actualizar la imagen. Revise el tipo de archivo o considere disminuir el tamaño');

            }

        } else { //almaceno descripcion de archivo (alt)

            if ($event->file) {
                $result = $event->file()->update(['file_alt'=> $request->get('file_alt')]);
            }

        }

        Session::keep(['redirect']);

        // DB::commit();
        return redirect()->route('events.edit', $event->id)->with('message', 'Los cambios en la imagen se aplicaron correctamente');
        
    } //END method


    /*=============================================
    Eliminar imagen de evento
    =============================================*/

    public function destroyImageEvent($id) {

        # Start transaction
        DB::beginTransaction();

        $event = Event::findOrFail($id);
        
        if($event->file) {

            $result_del = $this->deleteImages($event->file->file_path);
        
            $result_db = $event->file->delete();           

        }

        Session::keep(['redirect']);

        if ($result_db) {
            DB::commit();
            return redirect()->route('events.edit', $event->id)->with('message', 'La imagen se eliminó correctamente');
        } else {
            DB::rollBack();
            return back()->withErrors('Error al eliminar la imagen');
        }

    } //END method



    /*===================================================
    Crear y asociar un calendario-función a evento
    ====================================================*/

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


    // -------------------------------------------------------------------------
    // admin/events/{event}/calendars/{calendar}

    // Obtener datos de un calendario puntual, si pertenece a un evento dado
    // -------------------------------------------------------------------------

    public function getEventCalendar($id_event, $id_calendar)
    {

        $calendar = Calendar::where('event_id', (int)$id_event)->find((int)$id_calendar);

        return $calendar;

    }


}