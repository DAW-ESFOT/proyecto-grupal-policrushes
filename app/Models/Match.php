<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Match extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($match) {
            $match->user_id = Auth::id();
        });
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User');
    }

}
