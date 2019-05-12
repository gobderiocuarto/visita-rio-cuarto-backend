<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB; // Soporte para transacciones

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;
use App\Organization;

class ServiceController extends Controller
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
        // $services = Tag::orderBy('id', 'ASC')->paginate();
        $services = Tag::inGroup('Servicios')->paginate();

        // $pag = $services->currentPage();

        return view('admin.services.index', compact('services', 'pag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        # Start transaction
        DB::beginTransaction();

        $service = Tag::create($request->all());
        $service->setGroup('Servicios');

        $exist = Tag::where('slug', '=', $service->slug)->where('tag_group_id', '=', 1)->where('id', '<>',$service->id )->first();

        if ($exist) {

            DB::rollBack();
            return redirect()->back()->withErrors('Ya existe un servicio con el nombre ingresado');

        } else {
            DB::commit();
            return redirect('admin/services/' . $service->id.'/edit')->with('message', 'Servicio creado con éxito');
        }

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Obtener detalle del tag
        $service = Tag::findOrFail($id);

        // Organizaciones asociadas al grupo de tags "servicio" 
        $service_orgs = Organization::withAnyTag(["$service->name"])->orderBy('name','ASC')->paginate();

        // $list_orgs = Organization::where('state', 1)->orderBy('name','DESC')->get();

        $list_orgs = Organization::withoutTags(["$service->name"])->where('state', 1)->orderBy('name','DESC')->get();

        return view('admin.services.edit', compact('service', 'service_orgs', 'list_orgs') );

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

            $organization->save();

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
        $service = Tag::findOrFail($id);

        $tagged = Tagged::where('taggable_type','App\Organization')->where('tag_name',$service->name)->get();

        foreach ($tagged as $key => $tag) {

            if ($tag->taggable_type == "App\Organization") {
                $tag->delete();
                $org = Organization::findOrFail($tag->taggable_id)->save();
            }
        }

        $result_1 = $service->delete();

        return back()->with('message', 'Servicio eliminado correctamente');

    }



    public function storeOrganization(Request $request, $service_id)
    {

        $service = Tag::findOrFail($service_id);

        $organization = Organization::findOrFail($request->organization);

        if (!in_array($service->name, $organization->tagNames())) {
            $organization->tag("$service->name"); // attach the tag
            $organization->save();
            return redirect('admin/services/'.$service_id.'/edit')->with('message', 'Organización agregada con éxito');

        } else {
            return redirect()->back()->withErrors('Error al agregar la organización');
        }

    }


    public function destroyOrganization(Request $request, $service_id)
    {

        $service = Tag::findOrFail($service_id);

        $organization = Organization::findOrFail($request->organization);

        if (in_array($service->name, $organization->tagNames())) {
            $organization->untag("$service->name"); // attach the tag
            $organization->save();
            return redirect('admin/services/'.$service_id.'/edit')->with('message', 'Organización desvinculada con éxito');

        } else {
            return redirect()->back()->withErrors('Error al desvincular la organización');
        }

    }
}
