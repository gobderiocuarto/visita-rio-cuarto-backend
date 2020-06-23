<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'street_id', 'number', 'floor', 'lat', 'lng', 'zone_id'
    ];

    protected $appends = ['street'];


    public function spaces()
    {
        return $this->hasMany(Space::class);
    }

    public function places()
    {
        return $this->hasMany('App\Place');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // public function street()
    // {
    //     return $this->belongsTo(Street::class);
    // }


    //Retorna una calle unica para una direccion dada
    public function getStreetAttribute()
    {
    
        if ($street = Street::where("id", $this->street_id)->first()) {

            return $street;

        } else {

            $street = [
                "id" => "0",
                "name" => "Calle sin asignar",
                "slug" => "calle-sin-asignar"
            ];
            return (object)$street;

        }
    }

}
