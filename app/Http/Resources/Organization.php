<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\Place as PlaceResource;
use App\Http\Resources\File as FileResource;

class Organization extends JsonResource
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
            'id'            => $this->id,
            'category'      => $this->category->name,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'web'           => $this->web,
            'file'          => New FileResource($this->file),
            'tags'          => TagResource::collection($this->tagged),
            'places'        => PlaceResource::collection($this->places),
        ];
    }
}
