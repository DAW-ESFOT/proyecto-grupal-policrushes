<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movieable extends Model {
    use HasFactory;
    public $incrementing = TRUE;
    public $timestamps = TRUE;
}
