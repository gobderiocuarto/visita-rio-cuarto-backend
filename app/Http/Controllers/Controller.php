<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function getStreets()
    {
        $streets = json_decode(file_get_contents('http://eventos.localhost/files/streets/streets.json'), true);

        foreach ($streets as $key => &$street) {
            $street = (object)$street;
        }

        return $streets;

    }


    protected function renameFile($file) {

        $org_file_name = $file->getClientOriginalName();

        $pos_last_dot = strripos($org_file_name, '.');
        
        $file_name = substr($org_file_name,0,$pos_last_dot);
        $file_name = trim($file_name);

        $file_ext = $file->getClientOriginalExtension();

        $new_img = strtolower($file_name.'-'. date("Y-m-d-H-i-s") . '.' .$file_ext);

        $new_img = str_replace(" ", "-", $new_img);

        $new_img = filter_var($new_img, FILTER_SANITIZE_URL);

        return $new_img;
            
    }


}
