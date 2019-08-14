<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    
    protected $fillable = [
        'event_id', 'start_date', 'start_time', 'end_date', 'end_time', 'observations', 'state'
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
