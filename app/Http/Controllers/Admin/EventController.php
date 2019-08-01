<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Event;
use App\Category;
use App\Place;
use App\Street;
use App\Zone;
use App\AddressType;


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
        $event = Event::findOrFail($id);

        $tags_events = '';

        $group_type = 'App\Event';

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

        // $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();

        $zones = Zone::orderBy('name', 'ASC')->where('state',1)->get();
        $places = Place::orderBy('name', 'ASC')->get();
       
        $streets = $this->getStreets();

        $addresses_types = AddressType::orderBy('id', 'ASC')->where('state',1)->get();

        return view('admin.events.edit', compact('event','tags_events', 'places','zones', 'addresses_types', 'streets'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
