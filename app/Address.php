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
    //     return $this->belongsToMany(Organization::class)->withPivot('address_type_id', 'address_type_name');
    // }

    public function organizations()
    {
        return $this->morphToMany('App\Organization', 'organizationable')->withPivot('address_type_id', 'address_type_name');
    }

   
    public function places()
    {
        return $this->hasMany(Place::class);
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
