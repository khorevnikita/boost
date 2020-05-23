<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
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
        $hash = Cache::get('order_hash');
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
                $commonPrice = $commonPrice + $product->price;
                $orderProduct = $product->orderProducts()->where("order_id", $this->id)->with("options")->first();
                $product->selected_options = $orderProduct->options;
                foreach ($product->selected_options as $option) {
                    if ($option->type == "abs") {
                        $commonPrice = $commonPrice + $option->price;
                    } else {
                        $commonPrice = $product->price * $option->price / 100;
                    }
                }
            }
        }
        return $commonPrice;
    }

    public function bonus()
    {
        return $this->amount * config("marketing.bonus_value") / 100;
    }
}
