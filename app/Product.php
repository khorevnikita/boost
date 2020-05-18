<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }

    public function getBannerAttribute()
    {
        $img = $this->images()->first();
        if (!$img) {
            return null;
        }
        return $img->url;
    }
}
