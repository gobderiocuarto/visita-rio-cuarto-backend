<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Calendar extends JsonResource
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
            'start_date' => $this->start_date,
            'start_time' => $this->id,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'observations' => $this->observations,
        ];
    }
}
