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

        return [
            'id' => $this->id,
            'path_large'    => Storage::url("events/large/{$this->file_path}"),
            'path_medium'   => Storage::url("events/medium/{$this->file_path}"),
            'path_small'    => Storage::url("events/small/{$this->file_path}"),
            'alt' => $this->file_alt,
        ];
        
    }
}
