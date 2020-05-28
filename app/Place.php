<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    protected $fillable = [
        'place_id',
        'organization_id',
        'address_id', 
        'container', 
        'address_type_id', 
        'address_type_name', 
        'apartament'
    ];
    

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    
}
