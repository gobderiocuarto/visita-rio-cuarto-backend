<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
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
