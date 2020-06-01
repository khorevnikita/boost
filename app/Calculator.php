<?php

namespace App;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Calculator extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function calc($range)
    {
        $difference = $range->to - $range->from;
        if ($this->step_type === "abs") {
            $this->slider_price = $difference * $this->step_price;
        } else {
            $b1 = $this->start_value ? $this->start_value : 1;
            $q = (1 + $this->original_step_price / 100);
            $min_price = $b1 * ($q ** $range->from - 1) / ($q - 1);
            $max_price = $b1 * ($q ** $range->to - 1) / ($q - 1);
            return round($max_price - $min_price);
        }
        return 0;
    }

    public function getStepPriceAttribute($price)
    {
        $currency = Config::get("currency");
        if ($currency == "usd") {
            $exchangeRates = new ExchangeRate();
            $price = $exchangeRates->convert($price, 'EUR', 'USD', Carbon::now());
        }
        return round($price, 2);
    }

    public function getOriginalStepPriceAttribute()
    {
        return $this->attributes['price'];
    }
}
