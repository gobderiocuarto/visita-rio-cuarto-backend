<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'street_id', 'number', 'floor', 'lat', 'lng', 'zone_id'
    ];

    protected $appends = ['street'];


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


    // public function street()
    // {
    //     return $this->belongsTo(Street::class);
    // }

    //Retorna una calle unica para una direccion dada
    public function getStreetAttribute()
    {
        
        $streets = json_decode(file_get_contents(env('APP_URL').env('STREETS_PATH')), true);
        # Busca el id de calle en el array / listado de calles existentes (json)
        $key = array_search($this->street_id, array_column($streets , 'id'));

        if ($key === FALSE) {

            $street = [
                "id" => "0",
                "name" => "( Calle sin asignar )",
                "slug" => "calle-no-asignada"
            ];
            return (object)$street;
        } else {

            return (object)$streets[$key];
        }


    }


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
