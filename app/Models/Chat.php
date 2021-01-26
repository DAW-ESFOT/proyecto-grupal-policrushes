<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($chat) {
            $chat->user_id = Auth::id();
        });
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
