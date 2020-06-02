<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getOriginalBackgroundAttribute()
    {
        return $this->attributes['background'];
    }

    public function getBackgroundAttribute($bg)
    {
        if (!$bg) {
            return null;
        }
        return Storage::disk('public')->url($bg);
    }


    public function getOriginalObjectImageAttribute()
    {
        return $this->attributes['object_image'];
    }

    public function getObjectImageAttribute($img)
    {
        if (!$img) {
            return null;
        }
        return Storage::disk('public')->url($img);
    }


}
