<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Caffeinated\Shinobi\Models\Role;

use Session;

use App\Event;
use App\Category;
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

        // $this->middleware('auth');

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

        $group_id = Auth::user()->group->id;

        $events = $events->paginate(10);

        $events->appends((array)$filter);

        # Almacenar el query del listado actual para retornar 
        # a los ultimos parametros de busqueda, categoria, pagina desde la edición
        Session::flash('redirect',$request->getQueryString());

        return view('admin.events.index', compact('filter', 'events', 'event_tags', 'group_id'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $group = Auth::user()->group;

        $frame_events = Event::where('state', 1)->where('frame', 'is-frame')->get();

        Session::keep(['redirect']);

        return view('admin.events.create', compact('group', 'frame_events'));
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

            # Definido como evento marco
            if ($request->rel_frame == 'is-frame') {

                $request->request->add(['frame' => $request->rel_frame ]);

            } else {
                # No definido como evento marco
                $event_frame = Event::where('id', $request->rel_frame )->where('frame', 'is-frame')->first();

                if ($event_frame) {
                    $request->request->add(['event_id' => $request->rel_frame ]);
                }
            }

        }

        $request->request->add(['group_id' => Auth::user()->group->id ]);

        $event = Event::create($request->all());

        // $data['redirect'] = "";
        // if (Session::get('redirec_back')) {
        //     $data ['redirect']= Session::get('redirec_back');
        // }
        

        // DB::rollBack();

        Session::keep(['redirect']);

        if ($event) {

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
         echo ("<pre>");print_r("ver detalle del evento");echo ("</pre>"); exit();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        # Todos los tags agrupados en categoria eventos, para mostrar en el select
        $tags_group_events = Tag::inGroup('Eventos')->get()->toArray();

        # Tag categorizados bajo grupo "Eventos", asociados al evento puntual
        $tags_events = ''; // mostrar categorias como etiquetas separadas por coma
        $tags_in_event = []; // mostrar como array: select de categorias

        # Tag no categorizados bajo grupo "Eventos", asociados al evento puntual
        $tags_no_events = '';

        foreach ($event->tags as $tag) {

            if ($tag->isInGroup('Eventos')) {
                $tags_in_event[] = $tag ['name'];
            } else {
                $tags_no_events .= $tag ['name'].', ';
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
            
            $data = compact('event', 'tags_group_events', 'tags_in_event', 'tags_events', 'tags_no_events', 'calendar');

        } else {

            $frame_events = Event::where('state', 1)->where('frame', 'is-frame')->get();
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

            $data = compact('event', 'list_orgs', 'actual_place_id', 'actual_place',  'tags_group_events', 'tags_in_event', 'tags_events', 'tags_no_events', 'frame_events');
            
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

        # Verificar si el evento esta asignado a un grupo
        if ($event->group) {
            $group_id = $event->group->id;
        } else {
            $group_id = Auth::user()->group->id;
        }

        # Determinar Tags asociados

        # Tags manejados como categorias de eventos
        // Los tags categorizados bajo eventos se definen previamente, no pudiendose
        // agregar en forma dinámica desde el formulario de edición de eventos. 
        // Por mas que estos se cargen en el campo correspondiente a "categorias"
        // serán taggeados al evento pero se ubicaran en etiquetas asociadas
        // $tags_events = explode(',', $request->get('tags_events'));

        $tags_events = $request->get('select_mult');

        if (!$tags_events) {
            $tags_events = [];
        }

        # Tags no categorizados
        $tags_no_events = explode(',', $request->get('tags_no_events'));

        # Unir tags categorizados y no categorizados, almacenar 
        $tags =  array_merge($tags_events, $tags_no_events);
        $event->retag($tags);

        # Actualizar evento

        $update_fields = [
            'group_id' => $group_id,
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

                $event_frame = Event::where('id', $request->rel_frame )
                ->where('frame', 'is-frame')->first();
                
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

        $result = $event->update($update_fields);

        # END actualizar evento

        // Session::keep(['redirect']);

        if ($result) {
            DB::commit();
            // return back()->with('message', 'Evento actualizado correctamente');

            return redirect()->route('events.index', Session::get('redirect'))->with('message', 'Evento actualizado correctamente');
            
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
    Cargar imagen de evento
    =============================================*/

    public function loadImageEvent(Request $request, $id) {

        # Start transaction
        DB::beginTransaction();
        
        $event = Event::findOrFail($id);

        // echo ('<pre>');print_r($request->all());echo ('</pre>'); exit();
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            
            $folder_img = 'events/'.$event->id.'/';
            $thumb_img = $folder_img.'thumbs/';

            // Borrar archivos anteriores, si existen
            if($event->file) {
                $delete_file = $this->deleteFile($folder_img.$event->file->file_path);
                $delete_thumb = $this->deleteFile($thumb_img.$event->file->file_path);
                $event->file->delete();
            }
            
            // Renombrar archivo entrante
            $new_img = $this->renameFile($request->file('file'));
            
            # Cargado de imagen ... si ok devueve el path con la ubicación del archivo
            if( $path = Storage::putFileAs($folder_img, $request->file('file'), $new_img) ) {
                #Creamos el thumb 
                Storage::makeDirectory($thumb_img);
                $img = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$new_img ); 
                #Actualizamos db                     
                $result= $event->file()->create(['file_path'=> $new_img, 'file_alt'=> $request->get('file_alt') ]);
            } else {
                $result = FALSE;
            }

        } else {
            $result = FALSE;
            if($event->file) {
                $result = $event->file()->update(['file_alt'=> $request->get('file_alt')]);
            }
        }

        Session::keep(['redirect']);
        
        if ($result) {
            DB::commit();
            return redirect()->route('events.edit', $event->id)->with('message', 'Los cambios en la imagen se aplicaron correctamente');
        } else {
            DB::rollBack();
            return back()->withErrors('Error al aplicar los cambios en la imagen');
        }

    } //END method


    /*=============================================
    Eliminar imagen de evento
    =============================================*/

    public function destroyImageEvent($id) {

        # Start transaction
        DB::beginTransaction();

        // echo ('<pre>');print_r('TO DO eliminar imagen de evento: '.$id);echo ('</pre>'); exit();
        $event = Event::findOrFail($id);

        $folder_img = 'events/'.$event->id.'/';
        $thumb_img = $folder_img.'thumbs/';

        if($event->file) {
            $delete_file = $this->deleteFile($folder_img.$event->file->file_path);
            $delete_thumb = $this->deleteFile($thumb_img.$event->file->file_path);
            $result = $event->file->delete();
        }

        Session::keep(['redirect']);

        if ($result) {
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

}