<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use App\Organization;
use App\Place;
use App\Zone;

use App\Http\Requests\OrganizationStoreRequest;
use App\Http\Requests\OrganizationUpdateRequest;

class OrganizationController extends Controller
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
        $organizations = Organization::orderBy('id', 'ASC')->paginate();
        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $array_data = array();

        $array_data ['categories'] = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        //$array_data ['zones'] = Zone::orderBy('name', 'ASC')->where('state',1)->get();

        return view('admin.organizations.create', $array_data );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationStoreRequest $request)
    {
        $organization = Organization::create($request->all());
        return redirect()->route('organizations.edit', $organization->id)->with('message', 'Organización creada con éxito');
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
        $array_data ['organization'] = Organization::findOrFail($id);
        $array_data ['categories'] = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        $array_data ['places'] = Place::orderBy('name', 'ASC')->get();

        return view('admin.organizations.edit', $array_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationUpdateRequest $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $organization->fill($request->all())->save();

        return redirect()->route('organizations.edit', $organization->id)->with('message', 'Organización actualizada con éxito');
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
