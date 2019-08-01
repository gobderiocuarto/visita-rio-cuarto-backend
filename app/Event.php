<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use \Conner\Tagging\Taggable;

class Event extends Model
{
    
    use Taggable;

    protected $fillable = [
        'title', 'slug', 'summary', 'description'
    ];
}
