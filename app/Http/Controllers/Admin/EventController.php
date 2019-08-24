<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Event;
use App\Category;
use App\Place;
use App\Address;
use App\Calendar;
use App\Zone;
use App\Organization;

// Soporte para transacciones 
use Illuminate\Support\Facades\DB;


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

        $this->middleware('auth');

    }
    
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
            return back()->withErrors('Error al crear el evento');
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
        
        $places = $zones = $addresses_types = $streets = array();
        
        $event = Event::with('calendars')->findOrFail($id);
        
        #Listado total de organizaciones activas
        $organizations = Organization::where('state', 1)->orderBy('name')->with('addresses')->with('places.address')->get();
        
        #Determinar ubicación actual del evento
        $place = NULL;
        if ($event->place_id) {

            $org = Organization::Join('organizationables', 'organizationables.organization_id','organizations.id')
                                    ->where('organizationables.id', $event->place_id)
                                    ->select('organizations.*','organizationables.organizationable_id','organizationables.organizationable_type' )
                                    ->first();

            // var_dump($org); exit();

            if($org){
                $org = $org->toArray();
                switch ($org['organizationable_type']) {
                    case 'App\Place':
                        $actual_place = Place::with('address')->where('id',$org['organizationable_id'])->first();
                        $place = $org['name']." - ".$actual_place->name." - ".$actual_place->address->street->name." ".$actual_place->address->number;
                        // echo ('<pre>');print_r($place);echo ('</pre>'); exit();
        
                        // $actual_place['organization'] =
                        break;
                    case 'App\Address':
                        $actual_address = Address::where('id',$org['organizationable_id'])->first();
                        $place = $org['name']." ".$actual_address->street->name." ".$actual_address->number;
                        // echo ('<pre>');print_r($place);echo ('</pre>'); exit();
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }
        }

    
        // $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();
        // $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();
        // $streets = $this->getStreets();

        #Manejo de Tags 
        // dd($event->tagNames());

        # Recuperar Tag agrupados bajo "Eventos", pertenecientes al evento puntual
        $group_type = 'App\Event';

        $tags_events = '';
        $array_tags_in_event = [];
        $array_tags_events = Tagged::join('tagging_tags','tagging_tags.slug','tagging_tagged.tag_slug')
        ->join('tagging_tag_groups','tagging_tags.tag_group_id','tagging_tag_groups.id')
        ->where('tagging_tagged.taggable_id', $id )
        ->where('tagging_tagged.taggable_type', $group_type )
        ->where('tagging_tag_groups.slug', 'eventos')
        ->select('tagging_tags.name')
        ->get()->toArray();

        foreach ($array_tags_events as $key => $tag) {
            $tags_events .= $tag ['name'].', ';
            $array_tags_in_event[] = $tag ['name'];
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

        //Traer todos los tags agrupados en eventos
        $group_events = Tag::inGroup('Eventos')->get()->toArray();

        return view('admin.events.edit', compact('event', 'organizations', 'place',  'group_events', 'array_tags_in_event', 'tags_events', 'tags_no_events', 'places', 'zones', 'addresses_types', 'streets'));
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
        
        // dd($request);
        # Start transaction
        DB::beginTransaction();

        $event = Event::findOrFail($id);

        # Determinar Tags a Asociar

        #Tags manejados como categorias de eventos

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

        // echo ("<pre>");print_r($event->tagNames());echo ("</pre>"); exit();

        
        # Actualizar evento
        // $result = $event->fill($request->all())->save();
        $place = $event->place_id;
        
        if ($request->get('place_id')) {
            $place = $request->get('place_id');
        }
        
        $result = $event->update([
            'title'=> $request->get('title'),
            'slug'=> $request->get('slug'),
            'organizer'=> $request->get('organizer'),
            'summary'=> $request->get('summary'),
            'description'=> $request->get('description'),
            'state'=> $request->get('state'),
            'place_id'=> $place
        ]);
        // echo ('<pre>');print_r($event);echo ('</pre>'); exit();
        # END actualizar evento

        if ($result) {
            DB::commit();
            // return redirect()->route('events.edit', $event->id)->with('message', 'Evento actualizado correctamente');
            return back()->with('message', 'Evento actualizado correctamente');
            
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
        // echo ("<pre>");print_r("TO DO: eliminar evento");echo ("</pre>"); exit();
        $event = Event::findOrFail($id)->delete();
        return back()->with('message', 'Evento eliminado correctamente');
    }



    /*=============================================
    Cargar imagen de evento
    =============================================*/

    public function loadImageEvent(Request $request, $id) {

        // echo ('<pre>');print_r('TO DO subir imagen de evento: '.$id);echo ('</pre>'); exit();
        # Start transaction
        DB::beginTransaction();
        
        $event = Event::findOrFail($id);

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
        
        // var_dump($result); exit();

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

    
    
    // /*=============================================
    // Obtener HTML listado de funciones
    // =============================================*/

    // public function getHtmlEventFunction() {

    //      return view('admin.events.partials.event_calendar');

    // } //END method

}