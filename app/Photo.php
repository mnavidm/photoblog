<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $guarded = [];
    //
    public function album(){
        return $this->belongsTo(Album::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function isPhotoOwner()
    {
        return $this->album->user_id == auth()->id();
    }

}
