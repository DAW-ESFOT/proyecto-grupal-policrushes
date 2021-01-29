<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{
    //use HasFactory;
    protected $fillable = ['user2_id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($favorite) {
            $favorite->user1_id = Auth::id();
        });
    }

    public function user1()
    {
        return $this->belongsTo('App\Models\User', 'user1_id' );
    }
    public function user2()
    {
        return $this->belongsTo('App\Models\User', 'user2_id');
    }
}
