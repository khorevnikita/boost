<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public function calculator()
    {
        return $this->belongsTo(Calculator::class);
    }
}
