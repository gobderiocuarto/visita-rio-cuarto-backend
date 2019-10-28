<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Conner\Tagging\Taggable;

class Event extends Model
{
    
    use Taggable;

    protected $fillable = [
        'group_id', 'place_id', 'event_id', 'title', 'slug', 'summary', 'description', 'organizer', 'state', 'frame'
    ];


    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }



    public function calendars()
    {
        return $this->hasMany(Calendar::class)->orderBy('start_date','ASC');
    }
    

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
