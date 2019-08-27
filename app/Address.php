<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
        'street_id', 'number', 'floor', 'lat', 'lng', 'zone_id'
    ];

    protected $appends = ['street'];


    public function organizations()
    {
        return $this->morphToMany('App\Organization', 'organizationable')->withPivot('address_type_id', 'address_type_name');
    }

   
    public function spaces()
    {
        return $this->hasMany(Space::class);
    }


    // public function street()
    // {
    //     return $this->belongsTo(Street::class);
    // }

    //Retorna una calle unica para una direccion dada
    public function getStreetAttribute()
    {
        
        // $algo = env('APP_URL') . env('STREETS_PATH');

        // define('STREETS_URL',);

        // dd(STREETS_URL);
        // $streets = json_decode(file_get_contents(STREETS_URL), true);
        $streets = json_decode(file_get_contents(env('APP_URL').env('STREETS_PATH')), true);
        // $streets = json_decode(file_get_contents('http://eventos.localhost/files/streets/streets.json'), true);

        $key = array_search($this->street_id, array_column($streets , 'id'));

        return (object)$streets[$key];

    }


    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
