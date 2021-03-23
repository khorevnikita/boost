<?php

namespace App;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

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

        $hash = $_COOKIE["order_hash"] ?? null;
        if (!$hash) {
            return null;
        }
        return self::where('status', 'new')->where("hash", $hash)->first();
    }

    public function commonPrice()
    {
        $currency = Config::get("currency");
        $rate = Config::get("rate");

        $commonPrice = 0;
        if ($this->products->count() > 0) {
            foreach ($this->products as $product) {
                $commonPrice = $commonPrice + $product->price;
                #echo "Product price: $product->price <br>";
                if ($product->pivot->range) {
                    $calcPrice = $product->calculator->calc(json_decode($product->pivot->range));
                    $commonPrice = $commonPrice + $calcPrice;
                    # echo "Calc price: $calcPrice <br>";
                }

                # $orderProduct = $product->orderProducts()->where("order_id", $this->id)->with("options")->first();
                $selectedOptions = $product->getSelectedOptions($this);
                foreach ($selectedOptions->where("type", "abs") as $abs_option) {
                    $optPrice = $abs_option->price;
                    if ($currency !== $product->currency) {
                        if ($currency == "usd") {
                            $optPrice = $optPrice * $rate;
                        } else {
                            $optPrice = $optPrice / $rate;
                        }
                    }
                    #echo "Opt price: $optPrice <br>";
                    $commonPrice = $commonPrice + round($optPrice, 2);
                }
                foreach ($selectedOptions->where("type", "percent") as $p_option) {
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
        $rate = Config::get("rate");
        #dd(strtoupper($currency));
        if (strtoupper($currency) != $this->currency) {

            if ($currency === "usd") {
                $price = $price * $rate;
           } else {
                $price = $price / $rate;
            }
        }
        return round($price, 2);
    }

}
