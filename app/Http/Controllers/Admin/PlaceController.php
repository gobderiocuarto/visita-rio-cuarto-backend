<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


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
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->get();
        return view('admin.places.create', compact ('streets', 'zones') );
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
        
        // $address = Address::create ([
        //       'street_id' => $request->get('street') 
        //     , 'number' => $request->get('number')
        //     , 'floor' => $request->get('floor')
        //     , 'lat' => $request->get('lat')
        //     , 'lng' => $request->get('lng')
        //     , 'zone_id' => $request->get('zone')
        // ]);
        

        if (!$address) {

            DB::rollBack();

        } else {

            // $place = Place::create($request->all());
            $place = Place::create ([
                  'address_id' => $address->id
                , 'name' => $request->get('name') 
                , 'slug' => $request->get('slug')
                , 'description' => $request->get('description')
            ]);

            if (!$place) {
                DB::rollBack();
                return redirect()->back()->withErrors('Error al crear un espacio');

            } else {
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
        $address = Address::where('id', $id)->first();
        $streets = Street::orderBy('name', 'ASC')->get();
        $zones = Zone::orderBy('name', 'ASC')->get();

        //dd($array_data);

        return view('admin.places.edit', compact('place','address','streets','zones'));
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
        
        //dd($request->all());
        $place = Place::findOrFail($id);

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
            
        }

        $place->save();

        return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');

        // # Start transaction
        // // DB::beginTransaction();

        // //$place->fill($request->all())->save();

        // $place->name = $request->get('name'); 
        // $place->slug = $request->get('slug');
        // $place->description = $request->get('description');

        // // $result_1 = $place->save();
        // $result_1 = TRUE;

        // if (!$result_1) {

        //     DB::rollBack();

        // } else {

        //     //  'street_id', 'number', 'floor', 'lat', 'lng', 'zone_id'

        //     // $address = Address::where('id', $place->address_id)->update ([
        //     //       'street_id' => $request->get('street_id') 
        //     //     , 'number' => $request->get('number')
        //     //     , 'floor' => $request->get('floor')
        //     //     , 'lat' => $request->get('lat')
        //     //     , 'lng' => $request->get('lng')
        //     //     , 'zone_id' => $request->get('zone_id')
        //     // ]);

            

        //     $place->save();

        //     dd($place);





        //     if (!$address) {

        //         DB::rollBack();

        //     } else {

        //         if ($request->hasFile('file') && $request->file('file')->isValid()) {

        //             $folder_img = 'places/'.$place->id.'/';

        //             // Borrar archivos anteriores, si existen
        //             if($place->file) {

        //                 if (Storage::exists($folder_img.$place->file->file_path) ) {
        //                     Storage::delete($folder_img.$place->file->file_path);
        //                 }  
        //                 $place->file->delete();
        //             }

        //             // Renombrar archivo entrante
        //             $new_img = $this->renameFile($request->file('file'));

        //             if( $path = Storage::putFileAs($folder_img, $request->file('file'), $new_img) ) {

        //                 $thumb_img = $folder_img.'thumbs/';

        //                 Storage::makeDirectory($thumb_img);

        //                 $img = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$new_img );                      
 
        //                 $place->file()->create(['file_path'=> $new_img, 'file_alt'=> $request->get('file_alt') ]);

        //                 DB::commit();

        //                 return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');
                        
        //             } else {

        //                 DB::rollBack();
        //                 return back()->withInput()->withErrors(['Se produjo un error al subir el archivo. Por favor, intente nuevamente.']);

        //             }
                    
        //         } else {

        //            DB::commit(); 

        //            return redirect()->route('places.edit', $place->id)->with('message', 'Espacio actualizado con éxito');
        //         }
                
        //     }
        // }

        
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