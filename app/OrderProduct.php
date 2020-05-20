<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Model
{
    protected $table = "order_product";

    public function options()
    {
        return $this->belongsToMany(Option::class,'order_product_option');
    }
}
