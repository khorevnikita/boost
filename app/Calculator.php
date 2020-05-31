<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calculator extends Model
{
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
