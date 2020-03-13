<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;

use App\Http\Resources\Category as CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        # Coleccion general de categorías
        $categories = $this->getBaseCollection();

        # Recuperar categorias nivel superior
        $categories = $categories->where('category_id', 0);

        # Aplicar filtros querys
        $categories = $this->getQueries($request, $categories);

        return CategoryResource::collection($categories);
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
    public function show($category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();

        if (!$category) {
            abort(404);
        } 

        return New CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $categories = Category::where('categories.state', 1)
                                ->select('categories.*');
        # Orden de presentación
        $categories = $categories->orderBy('categories.name', 'ASC');

        return $categories;
    }


    private function getQueries(Request $request, $categories)
    {
        # Paginar registros, valor por defecto
        $paginate = 12; 

        # Almacenar los query a la url para mantenerlos en paginado de la consulta
        $query_filter = (object)[];

        # Filtrar por campo busqueda
        if (($request->search != '')) {
            $categories = $categories->where('categories.name', 'like', '%'.$request->search.'%')
                                     ->orWhere('categories.slug', 'like', '%'.$request->search.'%')
                                     ->orWhere('categories.description', 'like', '%'.$request->search.'%');

            $query_filter->search = $request->search;
        }

        # Paginar registros
        if ($request->paginate != '') {
            $paginate = $request->paginate;

            $query_filter->paginate = $request->paginate;
        } 
        $categories = $categories->paginate($paginate);

        # Agregar los query de la url al paginado de la consulta
        $categories->appends((array)$query_filter);

        return $categories;
    }
}
