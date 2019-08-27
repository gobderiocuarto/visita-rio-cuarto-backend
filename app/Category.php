<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;

class Category extends Model
{
    // use Searchable;
    
    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'state'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('name','ASC');
    }



} # END CLASS