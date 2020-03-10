<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Calendar as CalendarResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\EventFrame as EventFrameResource;
use App\Http\Resources\Place as PlaceResource;

# Imagenes
use Illuminate\Support\Facades\Storage;

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
            // 'group' => $this->group_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'state' => $this->state,
            'event_frame' => $this->when($this->event_id != NULL, New EventFrameResource(Event::find($this->event_id))),
            // 'place' => [
                //     'id' => $this->place['organization']['id'],
                //     'name' => $this->place['organization']['name'],
                //     'slug' => $this->place['organization']['slug'],
                //     'email' => $this->place['organization']['email'],
                //     'phone' => $this->place['organization']['phone'],
                //     'web' => $this->place['organization']['web'],
                // ],
                'place' => New PlaceResource($this->place),
                'tags' => TagResource::collection($this->tagged),
                'file' => [
                    'path' => $this->file->file_path,
                    'path_large'    => Storage::url("events/large/{$this->file->file_path}"),
                    'path_medium'   => Storage::url("events/medium/{$this->file->file_path}"),
                    'path_small'    => Storage::url("events/small/{$this->file->file_path}"),
                    'alt' => $this->file->file_alt,
                ],
                // 'calendars' => CalendarResource::collection($this->calendars),
                'calendars' => CalendarResource::collection($this->calendars->where('start_date', '>=', date("Y-m-d"))),
        ];
    }
}
