<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Place extends JsonResource
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
            'organization_id' => $this->organization_id,
            'organization' => $this->organization->name,
            'street' => $this->placeable->street->name,
            'number' => $this->placeable->number,
            'city' => $this->city,
            'administrative' => $this->administrative,
            'lat' => $this->placeable->lat,
            'lng' => $this->placeable->lng,
        ];
    }
}
