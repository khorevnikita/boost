<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute($v){
        if(!$v){
            return null;
        }
        return Storage::disk("public")->url($v);
    }
}
