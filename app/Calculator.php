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

        if ($this->steps && $this->steps->count() > 0) {
            if ($currency && Config::get('currency')=="usd") {
                $exchangeRates = new ExchangeRate();
                return round($exchangeRates->convert($difference, 'EUR', 'USD', Carbon::now()), 2);
            }
            return $difference;
        }

        if ($this->step_type === "abs") {
            if ($currency && Config::get('currency')=="usd") {
                $exchangeRates = new ExchangeRate();
                return round($exchangeRates->convert($difference * $this->step_price, 'EUR', 'USD', Carbon::now()), 2);
            }
            return $difference * $this->step_price;
        } else {
            $b1 = $this->start_value ? $this->start_value : 1;
            $q = (1 + $this->step_price / 100);
            $min_price = $b1 * ($q ** $range->from - 1) / ($q - 1);
            $max_price = $b1 * ($q ** $range->to - 1) / ($q - 1);
            if ($currency && Config::get('currency')=="usd") {
                $exchangeRates = new ExchangeRate();
                return round($exchangeRates->convert($max_price - $min_price, 'EUR', 'USD', Carbon::now()), 2);
            }
            return round($max_price - $min_price);
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
