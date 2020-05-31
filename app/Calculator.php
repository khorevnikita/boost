<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calculator extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function calc($range)
    {
        $difference = $range->to - $range->from;
        if ($this->step_type === "abs") {
            $this->slider_price = $difference * $this->step_price;
        } else {
            $b1 = $this->start_value ? $this->start_value : 1;
            $q = (1 + $this->step_price / 100);
            $min_price = $b1 * ($q ** $range->from - 1) / ($q - 1);
            $max_price = $b1 * ($q ** $range->to - 1) / ($q - 1);
            return round($max_price - $min_price);
        }
        return 0;
    }
}
