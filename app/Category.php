<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
