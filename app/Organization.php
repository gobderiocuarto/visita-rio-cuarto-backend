<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Conner\Tagging\Taggable;

class Organization extends Model
{
    
    use Taggable;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'email', 'phone', 'web'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class)->withPivot('address_type_id', 'address_type_name');
    }

    public function places()
    {
        return $this->belongsToMany(Place::class)->withPivot('address_type_id', 'address_type_name');
    }

}
