<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'street_id', 'number', 'floor', 'lat', 'lng', 'zone_id'
    ];

    // public function organizations()
    // {
    //     return $this->morphToMany('App\Organization', 'organizationable')->withPivot('address_type_id', 'address_type_name');
    // }

    public function spaces()
    {
        return $this->hasMany(Space::class);
    }


    public function places()
    {
        return $this->morphMany('App\Place', 'placeable');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function street()
    {
        return $this->belongsTo(Street::class);
    }


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
