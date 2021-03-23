<?php

namespace App;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot("range");
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function crosses()
    {
        return $this->belongsToMany(Product::class, 'crosses', 'original_product_id', 'remote_product_id');
    }

    public function calculator()
    {
        return $this->hasOne(Calculator::class);
    }

    public function getBannerAttribute()
    {
        $img = $this->images()->first();
        if (!$img) {
            return url("/images/no-banner.jpg");
        }
        return $img->url;
    }

    public function selectedOptions($order)
    {
        $orderProduct = $this->orderProducts()->where("order_id", $order->id)->with("options")->first();
        return $orderProduct->options;
    }

    public function getOptions()
    {
        $currency = Config::get("currency");
        $productCurrency = $this->currency ?: "eur";
        $rate = Config::get("rate");
        $k = 1;
        if ($currency !== $productCurrency) {
            if ($currency == "usd") {
                $k = $rate;
            } else {
                $k = 1 / $rate;
            }
        }
        return $this->options()->get()->map(function ($opt) use ($k) {
            if ($opt->type == "abs") {
                $opt->price = $k * $opt->price;
            }
            return $opt;
        });
    }
    public function getSelectedOptions($order)
    {
        $currency = Config::get("currency");
        $productCurrency = $this->currency ?: "eur";
        $rate = Config::get("rate");
        $k = 1;
        if ($currency !== $productCurrency) {
            if ($currency == "usd") {
                $k = $rate;
            } else {
                $k = 1 / $rate;
            }
        }
        return $this->selectedOptions($order)->map(function ($opt) use ($k) {
            if ($opt->type == "abs") {
                $opt->price = $k * $opt->price;
            }
            return $opt;
        });
    }


    public function getRatingAttribute()
    {
        return $this->assessments->avg("value");
    }

    public function getUrlAttribute()
    {
        if (!$this->category) {
            return null;
        }
        if (!$this->category->game) {
            return null;
        }
        return $this->category->game->rewrite . "/" . $this->rewrite;
    }

    public function getPriceAttribute($price)
    {
        $currency = Config::get("currency");
        $rate = Config::get("rate");
        #dd($rate);
        $productCurrency = $this->currency ?: "eur";

        if ($productCurrency != $currency) {
            if ($currency == "usd") {
                $price = $price * $rate;
            } else {
                $price = $price / $rate;
            }
        }

        return round($price, 2);
    }

    public function getOriginalPriceAttribute()
    {
        return $this->attributes['price'];
    }
}
