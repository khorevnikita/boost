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

    public function getColorFromAttribute()
    {
        try {
            $colors = json_decode($this->colors);
            return $colors->from;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getColorToAttribute()
    {
        try {
            $colors = json_decode($this->colors);
            return $colors->to;
        } catch (\Exception $e) {
            return null;
        }
    }

    /*public function setColorFromAttribute($value)
    {
        try {
            $colors = json_decode($this->colors);
            $colors->from = $value;
            $this->attributes["colors"] = $colors;
        } catch (\Exception $e) {

        }
    }
    public function setColorToAttribute($value)
    {
        try {
            $colors = json_decode($this->colors);
            $colors->to = $value;
            $this->attributes["colors"] = $colors;
        } catch (\Exception $e) {

        }
    }*/

    public function setColorsAttribute($v)
    {
        $this->attributes["colors"] = json_encode($v);
    }
}
