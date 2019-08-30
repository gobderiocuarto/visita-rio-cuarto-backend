<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    protected $fillable = [
        'organization_id', 'placeable_type', 'placeable_id', 'address_type_id', 'address_type_name', 'apartament'
    ];
    

    public function placeable()
    {
        return $this->morphTo();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    // public function addresses()
    // {
    //     return $this->belongsTo(Address::class);
    // }

    
}
