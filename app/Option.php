<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
