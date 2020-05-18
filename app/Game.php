<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Game extends Model
{
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function topDeals()
    {
        $products = Product::whereIn("category_id", $this->categories()->pluck("id"))->orderBy("id", "desc")->take(4)->get();
        return $products;
    }

    public function getBannerUrlAttribute()
    {
        if (!$this->banner) {
            return null;
        }
        return Storage::disk("public")->url($this->banner);
    }
}
