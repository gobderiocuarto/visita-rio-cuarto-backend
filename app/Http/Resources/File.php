<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

# Imagenes
use Illuminate\Support\Facades\Storage;

class File extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $base_folder = strtolower(str_replace("App\\", "", $this->fileable_type))."s";

        return [
            'id' => $this->id,
            'url_large'    => Storage::url( $base_folder."/large/{$this->file_path}"),
            'url_medium'   => Storage::url( $base_folder."/medium/{$this->file_path}"),
            'url_small'    => Storage::url( $base_folder."/small/{$this->file_path}"),
            'alt' => $this->file_alt,
        ];
        
    }
}