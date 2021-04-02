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

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function topDeals()
    {
        $products = Product::whereIn("category_id", $this->categories()->pluck("id"))->orderBy("id", "desc")->where("on_main", 1)->get();
        return $products;
    }

    public function getBannerUrlAttribute()
    {
        if (!$this->banner) {
            return null;
        }
        return Storage::disk("public")->url($this->banner);
    }

    public function getButtonIconUrlAttribute()
    {
        if (!$this->button_icon) {
            return null;
        }
        return Storage::disk("public")->url($this->button_icon);
    }


    public function getActualBannerAttribute()
    {
        return $this->banners()->where("published", 1)->first();
    }

    public function getMainProductsAttribute()
    {
        return Product::whereIn("category_id", $this->categories()->pluck("id"))->where("on_main", 1)->get();
    }
}
