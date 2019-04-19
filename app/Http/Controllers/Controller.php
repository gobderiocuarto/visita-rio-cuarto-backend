<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function upload_avatar($request, $id) {

        $image = $request->file('avatar');

        $org_file_name = $image->getClientOriginalName();

        $pos_last_dot = strripos($org_file_name, '.');
        
        $file_name = substr($org_file_name,0,$pos_last_dot);
        $file_name = trim($file_name);

        $file_ext = $image->getClientOriginalExtension();

        $new_img =  strtolower($file_name.'-'.$id. '-' . rand(0,999) . '.' .$file_ext);

        $path = $image->storeAs('organizations/category/'.$id, $new_img, 'public');

        if(!$path) {

            return FALSE;

        } else {

            return $new_img;

        }
            
    }
}
