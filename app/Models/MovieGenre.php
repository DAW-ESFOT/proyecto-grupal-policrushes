<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieGenre extends Model {
    use HasFactory;

    public $incrementing = TRUE;
    public $timestamps = TRUE;

    protected $fillable = [
        'name',
    ];

    public function users() {
        return $this->morphedByMany(MovieGenre::class, 'movieable');
    }

}
