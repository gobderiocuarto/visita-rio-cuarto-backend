<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CategoryStoreRequest;

use App\Http\Requests\CategoryUpdateRequest;

use App\Category;

class CategoryController extends Controller
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
        
        $categories = Category::where('category_id',0)->orderBy('name','ASC')->paginate(2);

        // $page = ($categories->currentPage() <= $categories->lastPage()) ? $categories->currentPage() : $categories->lastPage() ;

        // dd($page);

        // public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)

        // Model::find(...)->paginate($per_page, ['*'], 'page', $page);
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();
        return view('admin.categories.create', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {

        // dd($request);
        $category = Category::create($request->all());

        return redirect()->route('categories.edit', $category->id)->with('message', 'Categoría creada con éxito');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        
        $category = Category::findOrFail($id);

        $categories = Category::orderBy('name', 'ASC')->where('category_id',0)->where('id','<>',$category->id)->where('state',1)->get();

        $pag['list_page']= $request->get('pag');


        return view('admin.categories.edit', compact('category', 'categories'), $pag );
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->category_id <> 0) {

            if ($category->categories()->count()) {

               //dd("tiene hijas, NO puede");
                return redirect()->back()->withErrors('No es posible mover la categoría al nivel inferior porque contiene categorías hijas. Debe reubicarlas antes de proceder');

            }

        }

        $category->fill($request->all())->save();

        $list_page = (isset($request->list_page)) ? $request->list_page : 1 ;

        return redirect()->route('categories.edit', ['id'=> $category->id, 'pag'=>$list_page] )->with('message', 'Categoría actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        // $category = Category::findOrFail($id)->delete();

        if(empty($category->categories->toArray())) {
            $category->delete();
            return redirect()->back()->with('message', 'Categoría eliminada con éxito');
        }else {

            return redirect()->back()->withErrors('No puede eliminarse la categoria porque contiene subcategorias');

        }
    }
}