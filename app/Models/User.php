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
        'movies',
        'image'
    ];
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    private const ROLES_HIERARCHY = [
        self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
        self::ROLE_ADMIN => [self::ROLE_USER],
        self::ROLE_USER => []
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

    public function movies()
    {
        return $this->belongsToMany('App\Models\MovieGender', 'movie_genders')->withTimestamps();
    }

    public function music()
    {
        return $this->belongsToMany('App\Models\MusicGender', 'music_user')->withTimestamps();
    }

    public function chat()
    {
        return $this->hasMany('App\Models\Chat');
    }

    public function match()
    {
        return $this->hasMany('App\Models\Match');
    }

    public function favorite()
    {
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

    public function chats()
    {
        return $this->hasMany('App\Models\Chat');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite');
    }

    public function matchs()
    {
        return $this->hasMany('App\Models\Match');
    }

    public function music_genders()
    {
        return $this->hasMany('App\Models\MusicGender');
    }

    public function movie_genders()
    {
        return $this->hasMany('App\Models\MovieGender');
    }

    public function isGranted($role)
    {
        if ($role === $this->role) {
            return true;
        }
        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }

    private static function isRoleInHierarchy($role, $role_hierarchy)
    {
        if (in_array($role, $role_hierarchy)) {
            return true;
        }
        foreach ($role_hierarchy as $role_included) {
            if (self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$role_included])) {
                return true;
            }
        }
        return false;
    }
}
