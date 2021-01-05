<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'seen',
        'content',
    ];

    public function chat (){
        return $this->belongsToMany('App\Models\Chat');
    }
}
