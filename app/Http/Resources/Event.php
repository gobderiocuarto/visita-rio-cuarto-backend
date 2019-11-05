<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Calendar as CalendarResource;
use App\Http\Resources\Tag as TagResource;

class Event extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group' => $this->group_id,
            'title' => $this->title,
            'summary' => $this->summary,
            'slug' => $this->slug,
            'state' => $this->state,
            'place' => [
                'id' => $this->place['organization']['id'],
                'name' => $this->place['organization']['name'],
                'slug' => $this->place['organization']['slug'],
                'email' => $this->place['organization']['email'],
                'phone' => $this->place['organization']['phone'],
                'web' => $this->place['organization']['web'],
            ],
            'calendars' => CalendarResource::collection($this->calendars),
            'tags' => TagResource::collection($this->tagged),
            'file' => [
                'path' => 'http://admin.visitriocuarto.com/files/events/',
                'file' => $this->file['file_path']
            ],
        ];
    }
}
