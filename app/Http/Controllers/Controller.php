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



    /*protected function upload_file($file, $folders) {

        $org_file_name = $file->getClientOriginalName();

        $pos_last_dot = strripos($org_file_name, '.');
        
        $file_name = substr($org_file_name,0,$pos_last_dot);
        $file_name = trim($file_name);

        $file_ext = $file->getClientOriginalExtension();

        $new_img =  strtolower($file_name.'-'. rand(0,999) . '.' .$file_ext);

        //storeAs method receives the path, the file name, and the (optional) disk as its arguments:
        $path = $file->storeAs($folders, $new_img);

        if(!$path) {

            return FALSE;

        } else {

            return $new_img;

        }
            
    }
*/


    protected function renameFile($file) {

        $org_file_name = $file->getClientOriginalName();

        $pos_last_dot = strripos($org_file_name, '.');
        
        $file_name = substr($org_file_name,0,$pos_last_dot);
        $file_name = trim($file_name);

        $file_ext = $file->getClientOriginalExtension();

        $new_img =  strtolower($file_name.'-'. rand(0,999) . '.' .$file_ext);

        return $new_img;
            
    }



    /*public function setStorageResponse($type, $id) {

        // $json_object = json_encode(['id'=>$id,'type'=>$type]);

        //dd($json_object);

        // Setear el array el el local storage para recuperarlo luego de cargada la pag
        echo "<script>
            alert('hi');
        </script>";
    }*/



    protected function createThumbs($source_file_route, $subfolder_name, $pixel_size) {

        define("DS", DIRECTORY_SEPARATOR);

        $array_args = explode('/', $source_file_route);

        $source_file_route = implode(DS, $array_args);
        $source_file_route = public_path('files').DS.$source_file_route;

        $file = array_pop($array_args);

        $destiny_route = implode(DS, $array_args).DS.$subfolder_name;
        $destiny_route = public_path('files').DS.$destiny_route;


        if (!file_exists($destiny_route)){
            $result = mkdir($destiny_route,0777);
        } 



        //$temp = explode('.', $file);
        //$extension = $temp[1];

        $extension = strrchr($file,'.'); 
            
        switch ($extension){
        
            case '.gif':
                $create_from_source= imagecreatefromgif($source_file_route);
                break;
            case '.jpg':    
            case '.jpeg':
                $create_from_source= imagecreatefromjpeg($source_file_route);
                break;
            case '.png':
                $create_from_source= imagecreatefrompng($source_file_route);
                break;
            default:
                return false;
        };

        //Obtener ancho y alto de imagen
        list($source_file_width, $source_file_high) = getimagesize($source_file_route);

        if($source_file_width>$source_file_high){

            $new_alto = $pixel_size;
            $new_ancho = ($source_file_width/$source_file_high)*$new_alto;

            $x = ($source_file_width-$source_file_high)/2;
            $y = 0;

        }else{

            $new_ancho = $pixel_size;
            $new_alto = ($source_file_high/$source_file_width)*$new_ancho;

            $y = ($source_file_high-$source_file_width)/2;
            $x = 0;
        }


        $file_temp = imagecreatetruecolor($pixel_size, $pixel_size);
        imagecopyresampled($file_temp, $create_from_source, 0, 0, $x, $y, $new_ancho, $new_alto, $source_file_width, $source_file_high);

        
        switch ($extension){

            case '.gif':
                imagegif($file_temp, $destiny_route, 100);
                break;
            
            case '.jpg':
            case '.jpeg':
                imagejpeg($file_temp, $destiny_route, 100);
                break;
                
            case '.png':
                imagepng($file_temp, $destiny_route);
                //imagepng($tmp, $filename);
                break;
        };

        

        
        imagedestroy($file_temp);
        imagedestroy($create_from_source);

    }



    protected function resize_crop_image($source_file_route, $subfolder_name, $max_width, $max_height, $quality = 80){


        define("DS", DIRECTORY_SEPARATOR);

        $array_args = explode('/', $source_file_route);

        $source_file_route = implode(DS, $array_args);
        $source_file_route = public_path('files').DS.$source_file_route;

        $file = array_pop($array_args);

        $destiny_route = implode(DS, $array_args).DS.$subfolder_name;
        $destiny_route = public_path('files').DS.$subfolder_name;
        //$destiny_route = public_path('files').DS;


        if (!file_exists($subfolder_name)){
            $result = mkdir($subfolder_name,0777);
        } 



        $imgsize = getimagesize($source_file_route);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file_route);


        /*$width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if($width_new > $width){
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        }else{
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $subfolder_name, $quality);*/

        if($dst_img)imagedestroy($dst_img);
        if($src_img)imagedestroy($src_img);
        
    }


}
