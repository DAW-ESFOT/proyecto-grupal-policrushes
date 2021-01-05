<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    public function user (){
        return $this->belongsToMany('App\Models\User');
    }

    public function message (){
        return $this->hasMany('App\Models\Message');
    }
}
