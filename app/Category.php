<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'state'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }


} # END CLASS