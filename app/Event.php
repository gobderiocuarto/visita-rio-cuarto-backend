<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Conner\Tagging\Taggable;

class Event extends Model
{
    
    use Taggable;

    protected $fillable = [
        'group_id', 'place_id', 'title', 'slug', 'summary', 'description', 'organizer', 'state'
    ];


    public function calendars()
    {
        return $this->hasMany(Calendar::class)->orderBy('start_date','ASC');
    }
    

    // public function files()
    // {
    //     return $this->morphMany(File::class, 'fileable');
    // }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
