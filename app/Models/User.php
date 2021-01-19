<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        //business
        'gender',
        'age',
        'description',
        'location',
        'address',
        'min_age',
        'max_age',
        'preferred_gender',
        'preferred_pet',
        'movies'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function movies (){
        return $this->belongsToMany('App\Models\MovieGender')->withTimestamps();
    }

    public function music (){
        return $this->belongsToMany('App\Models\MusicGender')->withTimestamps();
    }

    public function chat (){
        return $this->hasMany('App\Models\Chat');
    }

    public function match (){
        return $this->hasMany('App\Models\Match');
    }

    public function favorite (){
        return $this->hasMany('App\Models\Favorite');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
