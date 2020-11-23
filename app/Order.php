<?php

namespace App;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot("range");
    }

    public static function findTheLast(User $user = null)
    {
        if ($user) {
            $order = $user->activeOrder();
        } else {
            $order = self::findByHash();
        }

        return $order;
    }

    public static function findByHash()
    {

        $hash = $_COOKIE["order_hash_" . config("app.cookie_key")] ?? "";

        if (!$hash) {
            return null;
        }
        return self::where('status', 'new')->where("hash", $hash)->first();
    }

    public function commonPrice()
    {
        $commonPrice = 0;
        if ($this->products->count() > 0) {
            foreach ($this->products as $product) {
                $commonPrice = $commonPrice + $product->original_price;
                if ($product->pivot->range) {
                    $commonPrice = $commonPrice + $product->calculator->calc(json_decode($product->pivot->range));
                }

                $orderProduct = $product->orderProducts()->where("order_id", $this->id)->with("options")->first();
                $product->selected_options = $orderProduct->options;
                foreach ($product->selected_options->where("type", "abs") as $abs_option) {
                    $commonPrice = $commonPrice + $abs_option->original_price;
                }
                foreach ($product->selected_options->where("type", "percent") as $p_option) {
                    $commonPrice = $commonPrice + $commonPrice * $p_option->original_price / 100;
                }
            }
        }
        return $commonPrice;
    }

    public function bonus()
    {
        return $this->amount * config("marketing.bonus_value") / 100;
    }

    public function getCreatedAtAttribute($v)
    {
        return Carbon::parse($v)->format("H:i d.m.Y");
    }

    public function getPayedAtAttribute($v)
    {
        if (!$v) {
            return null;
        }
        return Carbon::parse($v)->format("H:i d.m.Y");
    }

    public function getAmountAttribute($price)
    {
        $currency = Config::get("currency");
        if ($currency == "usd") {
            $exchangeRates = new ExchangeRate();
            $price = $exchangeRates->convert($price, 'EUR', 'USD', Carbon::now());
        }
        return round($price, 2);
    }

}
