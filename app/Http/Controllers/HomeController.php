<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;

use App\Zone;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $array_data = array();

        $array_data ['categories'] = Category::orderBy('name', 'ASC')->where('category_id',0)->where('state',1)->get();

        $array_data ['zones'] = Zone::orderBy('name', 'ASC')->get();

        return view('web.home', $array_data);
    }
}
