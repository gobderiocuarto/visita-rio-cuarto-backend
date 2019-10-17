<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'state'
    ];

    public function events()
    {
        return $this->belongsToMany('App\Event')->withPivot('event_id', 'group_id');
    }
}
