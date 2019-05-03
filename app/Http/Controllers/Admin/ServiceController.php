<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;
use App\Organization;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $services = Tag::orderBy('id', 'ASC')->paginate();
        $services = Tag::inGroup('Servicios')->paginate();

        // $pag = $services->currentPage();
        // dd($pag);

        return view('admin.services.index', compact('services', 'pag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $service = Tag::findOrFail($id);

        $organizations = Organization::withAnyTag(["$service->name"])->get();

        return view('admin.services.edit', compact('service', 'organizations') );

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
        $service = Tag::findOrFail($id);

        $old_name = $service->name;

        $service->fill($request->all())->save();

        $organizations = Organization::withAnyTag(["$old_name"])->get();


        foreach ($organizations as $organization) {

            $result = Tagged::where('taggable_id',$organization->id)
                            ->where('taggable_type','App\Organization')
                            ->where('tag_name',$old_name)
                            ->update(['tag_name'=>$service->name , 'tag_slug'=>$service->slug]);
            // $result = Tagged::where('taggable_id',$organization->id)->where('taggable_type','App\Organization')->get();

            //dd($result);

        }

        $service->fill($request->all())->save();


        return redirect('admin/services/' . $service->id.'/edit')->with('message', 'Servicio actualizado con éxito');

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
