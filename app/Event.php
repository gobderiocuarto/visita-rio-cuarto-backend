<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Conner\Tagging\Taggable;

class Event extends Model
{
    
    use Taggable;

    protected $fillable = [
        'place_id', 'title', 'slug', 'summary', 'description', 'organizer'
    ];


    public function calendars()
    {
        return $this->hasMany(Calendar::class)->orderBy('start_date','ASC');
    }
}
