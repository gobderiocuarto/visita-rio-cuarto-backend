<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Calendar as CalendarResource;
use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\EventFrame as EventFrameResource;
use App\Http\Resources\Place as PlaceResource;
use App\Http\Resources\File as FileResource;

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
            'place' => New PlaceResource($this->place),
            'tags' => TagResource::collection($this->tagged),
            'file' => New FileResource($this->file),
            'calendars' => CalendarResource::collection($this->calendars),
            // 'calendars' => CalendarResource::collection($this->calendars->where('start_date', '>=', date("Y-m-d"))),
        ];
    }
}
