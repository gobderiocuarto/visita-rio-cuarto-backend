<?php
namespace App\Traits;

# Imagenes
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

trait ImageTrait  {

    # Borrar archivos, si existen
    protected function deleteFile($file_path) {

        if (Storage::exists($file_path)) {
            return Storage::delete($file_path);
        }  else {
            return FALSE;
        }
    }

    # Cargado de imagen
    /* public function loadImage(Request $request, $folder_img, $thumb_img) {

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
                
            
            // Renombrar archivo entrante
            $new_img = $this->renameFile($request->file('file'));
            
            if( $path = Storage::putFileAs($folder_img, $request->file('file'), $new_img) ) {
                
                Storage::makeDirectory($thumb_img);
                $img = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$new_img );                      
                $event->file()->create(['file_path'=> $new_img, 'file_alt'=> $request->get('file_alt') ]);
            }
            
        } else {
            $event->file()->update(['file_alt'=> $request->get('file_alt')]);
        }
    } # END  Cargado de imagen */



    // protected function renameFile(Request $request) {

    //     if ($request->hasFile('file') && $request->file('file')->isValid()) {

    //         $new_img = $this->renameFile($request->file('file'));

    //         $org_file_name = $file->getClientOriginalName();

    //         $pos_last_dot = strripos($org_file_name, '.');
            
    //         $file_name = substr($org_file_name,0,$pos_last_dot);
    //         $file_name = trim($file_name);

    //         $file_ext = $file->getClientOriginalExtension();

    //         $new_img = strtolower($file_name.'-'. date("Y-m-d-H-i-s") . '.' .$file_ext);

    //         $new_img = str_replace(" ", "-", $new_img);

    //         $new_img = filter_var($new_img, FILTER_SANITIZE_URL);

    //         return $new_img;
            
    //     } else {
    //         return FALSE;
    //     }
            
    // }

} # END trait