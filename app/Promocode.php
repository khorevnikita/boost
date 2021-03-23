<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
