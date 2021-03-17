<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordInterface;
use JWTAuth;

class User extends Authenticatable implements JWTSubject, CanResetPasswordInterface
{
    use HasFactory;
    use Notifiable;
    use CanResetPassword;

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
        'image',
        'lat',
        'lng',
        'birthdate'
    ];
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    private const ROLES_HIERARCHY = [
        self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
        self::ROLE_ADMIN      => [self::ROLE_USER],
        self::ROLE_USER       => []
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


    public function chat() {
        return $this->hasMany('App\Models\Chat');
    }

    public function match() {
        return $this->hasMany('App\Models\Match');
    }

    public function favorite() {
        return $this->hasMany('App\Models\Favorite');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function chats() {
        return $this->hasMany('App\Models\Chat');
    }

    public function favorites() {
        return $this->hasMany('App\Models\Favorite');
    }

    public function matchs() {
        return $this->hasMany('App\Models\Match');
    }


    public function isGranted($role) {
        if ($role === $this->role) {
            return TRUE;
        }
        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }

    private static function isRoleInHierarchy($role, $role_hierarchy) {
        if (in_array($role, $role_hierarchy)) {
            return TRUE;
        }
        foreach ($role_hierarchy as $role_included) {
            if (self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$role_included])) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Get all of the music genres for the user.
     */
    public function musicGenres() {
        return $this->morphToMany(MusicGenre::class, 'musicable')->withTimestamps();
    }

    /**
     * Get all of movie genres for the user.
     */
    public function movieGenres() {
        return $this->morphToMany(MovieGenre::class, 'movieable')->withTimestamps();
    }

    /**
     * Attach music genres
     */
    public function attachMusicGenres($names) {

        $genres = [];
        foreach ($names as $name) {

            $musicGenre = MusicGenre::where('name', $name)->first();
            if (!$musicGenre) continue;

            $genres[] = $musicGenre->id;
        }

        $this->musicGenres()->detach();
        $this->musicGenres()->attach($genres);
    }

    /**
     * Attach movie genres
     */
    public function attachMovieGenres($names) {

        $genres = [];
        foreach ($names as $name) {

            $movieGenre = MovieGenre::where('name', $name)->first();
            if (!$movieGenre) continue;

            $genres[] = $movieGenre->id;
        }

        $this->movieGenres()->detach();
        $this->movieGenres()->attach($genres);
    }

    public function musicGenresNames() {

        return $this->musicGenres->map(
            function ($musicGenre) {
                return $musicGenre->name;
            }
        );
    }

    public function movieGenresNames() {

        return $this->movieGenres->map(
            function ($movieGenre) {
                return $movieGenre->name;
            }
        );
    }

    public function imageUrl() {

        $host       = request()->getHttpHost();
        $path       = $this->image;
        $publicPath = $path ? Storage::url($path) : NULL;
        $imageUrl   = $path ? $host . $publicPath : NULL;
        return $imageUrl;
    }

    public function imagePath() {

        $path        = Storage::url($this->image);
        $public_path = public_path($path);
        return $public_path;
    }

    public function completeRecord() {

        return array_merge($this->toArray(), [
            "music_genres" => $this->musicGenresNames(),
            "movie_genres" => $this->movieGenresNames(),
            "imageUrl"     => $this->imageUrl(),
            "token" => JWTAuth::fromUser($this)
        ]);
    }

}
