<?php

namespace App;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Option extends Model
{
    /**
     * Связь доступных продукту опций
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Привязанные опции к заказу
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function orderProduct()
    {
        return $this->belongsToMany(OrderProduct::class,'order_product_option');
    }

    public function getPriceAttribute($price)
    {
        $currency = Config::get("currency");
        if ($currency=="usd") {
            $exchangeRates = new ExchangeRate();
            $price = $exchangeRates->convert($price, 'EUR', 'USD', Carbon::now());
        }
        return round($price, 2);
    }

    public function getOriginalPriceAttribute()
    {
        return $this->attributes['price'];
    }
}
