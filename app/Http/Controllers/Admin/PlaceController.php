<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


use App\Category;
use App\Address;
use App\Place;
use App\Street;
use App\Zone;

use App\Http\Requests\PlaceStoreRequest;

use Intervention\Image\ImageManagerStatic as Image;

class PlaceController extends Controller
{

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
        $places = Place::orderBy('id', 'ASC')->paginate();
        return view('admin.places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        // $streets = Street::orderBy('name', 'ASC')->get();
        $streets = $this->getStreets(); // in Controller (parent)
        $zones = Zone::orderBy('name', 'ASC')->get();
        return view('admin.places.create', compact ('categories', 'streets', 'zones') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(PlaceStoreRequest $request)
    {

        //dd($request);

        # Start transaction
        DB::beginTransaction();

        $address = Address::create($request->all());
             
        if (!$address) {

            DB::rollBack();

        } else {

            $place = Place::create ([
                  'address_id' => $address->id
                , 'category_id' => $request->get('category_id')
                , 'name' => $request->get('name') 
                , 'slug' => $request->get('slug')
                , 'description' => $request->get('description')
            ]);

            if (!$place) {
                DB::rollBack();
                return redirect()->back()->withErrors('Error al crear un espacio');

            } else {

                $tags = explode(',', $request->get('tags'));
                $place->tag($tags);

                foreach ($place->tags as $tag) {
                   $tag->setGroup('Servicios');
                }

                $place->update();

                DB::commit();
                return redirect()->route('places.edit', $place->id)->with('message', 'Espacio creado con éxito');
            }
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

        $place = Place::findOrFail($id);

        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        $address = Address::where('id', $id)->first();
        $zones = Zone::orderBy('name', 'ASC')->get();

        // $streets = Street::orderBy('name', 'ASC')->get();
        $streets = $this->getStreets(); // in Controller (parent)

        $tags = implode(', ', $place->tagNames());

        //dd($array_data);

        return view('admin.places.edit', compact('place','address','streets','zones', 'tags', 'categories'));
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
        
        $place = Place::findOrFail($id);

        $tags = explode(',', $request->get('tags'));
        $place->retag($tags);

        foreach ($place->tags as $tag) {
           $tag->setGroup('Servicios');
        }

        $place->address->fill($request->all())->save();

        // dd($place->address);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $folder_img = 'places/'.$place->id.'/';
            $thumb_img = $folder_img.'thumbs/';

            // Borrar archivos anteriores, si existen
            if($place->file) {

                if (Storage::exists($folder_img.$place->file->file_path) ) {
                    Storage::delete($folder_img.$place->file->file_path);
                    Storage::delete($thumb_img.$place->file->file_path);
                }  
                $place->file->delete();
            }

            // Renombrar archivo entrante
            $new_img = $this->renameFile($request->file('file'));

            if( $path = Storage::putFileAs($folder_img, $request->file('file'), $new_img) ) {

                Storage::makeDirectory($thumb_img);

                $img = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$new_img );                      

                $place->file()->create(['file_path'=> $new_img, 'file_alt'=> $request->get('file_alt') ]);

                // DB::commit();

                // return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');
                
            }
            
        } else {
            $place->file()->update(['file_alt'=> $request->get('file_alt')]);
        }

        $place->fill($request->all())->save();

        return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::findOrFail($id)->delete();
        return back()->with('message', 'Espacio eliminado correctamente');
    }

}