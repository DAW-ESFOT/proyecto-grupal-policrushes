<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MovieGender extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($moviegender) {
            $moviegender->user_id = Auth::id();
        });
    }

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'movie_genders')->withTimestamps();
    }
}
