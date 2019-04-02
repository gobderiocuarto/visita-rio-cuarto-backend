<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'email', 'phone', 'web'
    ];
}
