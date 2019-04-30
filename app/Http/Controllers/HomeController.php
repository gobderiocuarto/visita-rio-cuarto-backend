<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\Zone;
use App\Organization;


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

        $organizations = Organization::orderBy('id', 'ASC')->paginate();
        //dd($array_data);
        return view('web.home.index', compact('organizations'));
    }



    public function search(Request $request)
    {
        
        $query = $request->get('search');
        // $organizations = Organization::with('tagged')->where('name', 'like',"%$query%")->get();
        $organizations = Organization::search($query)->get();
        
        //dd($organizations);
        return view('web.home.index', compact('organizations'));
    }

}
