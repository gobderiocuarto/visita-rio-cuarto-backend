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
        // echo ('<pre>');print_r($request->all());echo ('</pre>'); exit();
        
        return [
            'id' => $this->id,
            'group' => $this->group_id,
            'title' => $this->title,
            // 'summary' => $this->summary,
            // 'slug' => $this->slug,
            // 'state' => $this->state,
            'calendars' => CalendarResource::collection($this->calendars),
            // 'calendars' => CalendarResource::collection($this->calendars->where('start_date', '>=', '2020-02-20')),
            'place' => [
                'id' => $this->place['organization']['id'],
                'name' => $this->place['organization']['name'],
                'slug' => $this->place['organization']['slug'],
                'email' => $this->place['organization']['email'],
                'phone' => $this->place['organization']['phone'],
                'web' => $this->place['organization']['web'],
            ],
            
            'tags' => TagResource::collection($this->tagged),
            'file' => [
                'path' => env('APP_URL').'/files/events/'.$this->id.'/',
                'file' => $this->file['file_path']
            ],
        ];
    }
}
