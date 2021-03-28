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

    public function steps()
    {
        return $this->hasMany(Step::class);
    }


    public function calc($range, $currency = false)
    {
        $difference = $range->to - $range->from;
        $currency = Config::get("currency");
        $rate = Config::get("rate");
        $productCurrency = $this->product->currency ?: "eur";

        if ($this->steps && $this->steps->count() > 0) {
            if ($productCurrency !== $currency) {
                if ($currency == "eur") {
                    $difference = $difference * $rate;
                } else {
                    $difference = $difference / $rate;
                }
            }
            return round($difference, 2);
        }

        if ($this->step_type === "abs") {
            if ($productCurrency !== $currency) {
                if ($currency == "eur") {
                    $difference = $difference / $rate;
                } else {
                    $difference = $difference * $rate;
                }
            }
            return round($difference * $this->step_price,2);
        } else {
            $b1 = $this->start_value ? $this->start_value : 1;
            $q = (1 + $this->step_price / 100);
            $min_price = $b1 * ($q ** $range->from - 1) / ($q - 1);
            $max_price = $b1 * ($q ** $range->to - 1) / ($q - 1);
            $priceDiff = $max_price - $min_price;
            if ($productCurrency !== $currency) {
                if ($currency == "eur") {
                    $priceDiff = $priceDiff * $rate;
                } else {
                    $priceDiff = $priceDiff / $rate;
                }
            }
            return round($priceDiff,2);
        }
    }

    /*public function getStepPriceAttribute($price)
    {
        $currency = Config::get("currency");
        if ($currency == "usd") {
            $exchangeRates = new ExchangeRate();
            $price = $exchangeRates->convert($price, 'EUR', 'USD', Carbon::now());
        }
        return round($price, 2);
    }*/

    public function getOriginalStepPriceAttribute()
    {
        return $this->attributes['step_price'];
    }
}
