<?php

namespace App\Http\Controllers\Api;

use App\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Organization as OrganizationResource;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # coleccion general de organizaciones
        $organization = $this->getBaseCollection();

        $organization = $this->getQueries($request, $organization);

        return OrganizationResource::collection($organization);
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
    
    public function show($service_slug)
    {
        $organization = Organization::where('slug', $service_slug)->first();

        if (!$organization) {
            abort(404);
        } 

        return New OrganizationResource($organization);
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



    /*--------------------------------------/*
    /*---------------------------------------
        Metodos privados
    /*---------------------------------------
    /*--------------------------------------*/

    private function getBaseCollection()
    {
        $organization = Organization::where('organizations.state', 1)
                                    ->select('organizations.*');
        
        # Orden de presentación
        $organization = $organization->orderBy('organizations.name', 'ASC');

        return $organization;

    }


    private function getQueries(Request $request, $organizations)
    {
        # Paginar registros, valor por defecto
        $paginate = 12; 

        # Almacenar los query a la url para mantenerlos en paginado de la consulta
        $query_filter = (object)[];

        # Filtrar por campo busqueda
        if (($request->search != '')) {
            $organizations = $organizations->where('organizations.name', 'like', '%'.$request->search.'%' );
            $query_filter->search = $request->search;
        }

        # Filtrar por categoría (id)
        if (((int)$request->category != 0 )) {
            $organizations = $organizations->join ('categories','categories.id','organizations.category_id')
            ->where(function ($query) use ($request) {
                $query  ->where ('categories.id', '=', $request->category)
                        ->orWhere ('categories.category_id', '=', $request->category);
            });

            $query_filter->category = $request->category;
        }

        # Paginar registros
        if ($request->paginate != '') {
            $paginate = $request->paginate;

            $query_filter->paginate = $request->paginate;
        } 
        $organizations = $organizations->paginate($paginate);

        # Agregar los query de la url al paginado de la consulta
        $organizations->appends((array)$query_filter);

        return $organizations;
    }
}