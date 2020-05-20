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
        return self::where("hash", $hash)->first();
    }
}
