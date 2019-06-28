<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Conner\Tagging\Taggable;

class Place extends Model
{
    
    use Taggable;

    //protected $guarded = [];

    protected $fillable = [
        'category_id', 'address_id', 'name', 'slug', 'description'
    ];

    
    public function address()
    {
        return $this->belongsTo(Address::class);
    }


    // public function organizations()
    // {
    //     return $this->belongsToMany(Organization::class)->withPivot('address_type_id', 'address_type_name');
    // }


    public function organizations()
    {
        return $this->morphToMany('App\Organization', 'organizationable')->withPivot('address_type_id', 'address_type_name');
    }


    // public function files()
    // {
    //     return $this->morphMany(File::class, 'fileable');
    // }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }


}