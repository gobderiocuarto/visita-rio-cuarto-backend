<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    
    protected $fillable = [
        'fileable_id', 'fileable_type', 'file_path', 'file_alt'
    ];


    public function fileable() 
    {
    	return $this->morphTo();
    }
}