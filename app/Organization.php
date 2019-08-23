<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Conner\Tagging\Taggable;

use Laravel\Scout\Searchable;

class Organization extends Model
{
    
    use Taggable;
    use Searchable;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'email', 'phone', 'web'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function addresses()
    // {
    //     return $this->belongsToMany(Address::class)->withPivot('address_type_id', 'address_type_name');
    // }

    public function addresses()
    {
        return $this->morphedByMany('App\Address', 'organizationable')->withPivot('id','address_type_id', 'address_type_name');
    }

    // public function places()
    // {
    //     return $this->belongsToMany(Place::class)->withPivot('address_type_id', 'address_type_name');
    // }


    public function places()
    {
        return $this->morphedByMany('App\Place', 'organizationable')->withPivot('id','address_type_id', 'address_type_name');
    }
    

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function toSearchableArray() {
        $this->load('tagged');
        $this->load('category.category');
        return $this->toArray();
    }


}
