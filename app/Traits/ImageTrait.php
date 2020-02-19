<?php
namespace App\Traits;

# Imagenes
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

trait ImageTrait  {

    protected $file_types = ['image/jpeg', 'image/gif', 'image/png'];

    protected $large_width; 
    protected $medium_width;
    protected $small_width; 

    protected $folder_img     = 'events/';

    # Borrar archivos, si existen
    private function deleteFile($file_path) {

        if (Storage::exists($file_path)) {
            return Storage::delete($file_path);
        }  else {
            return FALSE;
        }
    }


    protected function deleteImages($file_path) {

        $large_folder   = $this->folder_img.'large/';
        $medium_folder  = $this->folder_img.'medium/';
        $small_folder   = $this->folder_img.'small/';

        $delete_large   = $this->deleteFile($large_folder.$file_path);
        $delete_medium  = $this->deleteFile($medium_folder.$file_path);
        $delete_small   = $this->deleteFile($small_folder.$file_path);

        if ($delete_large && $delete_medium  && $delete_small ){

            return TRUE;
        }
        
        return FALSE;
    }


    # Cargado y resampleado de imagenes
    protected function loadImages($request, $img_name) {

        $large_folder   = $this->folder_img.'large/';
        $medium_folder  = $this->folder_img.'medium/';
        $small_folder   = $this->folder_img.'small/';


        if (Storage::put($large_folder.$img_name, fopen($request->file('file'), 'r+'), 'public') ) {

            # Obj Intervention Image
            $new_img = Image::make(Storage::get($large_folder.$img_name));

            #------------------------------
            # Large
            #------------------------------

            if ($new_img->width() > $this->large_width) {

                # Resampleamos la imagen a large
                $new_img->resize($this->large_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                # Sobreescribimos el archivo almacenado
                Storage::put($large_folder.$img_name, $new_img->stream()->__toString() , 'public');
            }

            # Crear subdirectorio
            // Storage::makeDirectory($thumb_img, 'public');

            #------------------------------
            # END Large
            #------------------------------


            #------------------------------
            # Medium
            #------------------------------

            # Resampleamos la imagen a medium
            $new_img->resize($this->medium_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            # Sobreescribimos el archivo almacenado
            Storage::put($medium_folder.$img_name, $new_img->stream()->__toString() , 'public');

            #------------------------------
            # END Medium
            #------------------------------


            #------------------------------
            # Small
            #------------------------------

            # Resampleamos la imagen a small
            $new_img->resize($this->small_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            # Sobreescribimos el archivo almacenado
            Storage::put($small_folder.$img_name, $new_img->stream()->__toString() , 'public');

            #------------------------------
            # END Small
            #------------------------------


            #------------------------------
            # Thumbnails
            #------------------------------
            
            # Crear imagen resampleada
            // $thumb = Image::make(Storage::get($folder_img.$img_name))->fit(250, 250);
            // $thumb = Image::make(Storage::get($path))->fit(250, 250)->save('files/'.$thumb_img.$img_name );
            
            // Storage::put($thumb_img.$img_name, $thumb->stream()->__toString(), 'public');

            return TRUE;            
        } 

        return FALSE;

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