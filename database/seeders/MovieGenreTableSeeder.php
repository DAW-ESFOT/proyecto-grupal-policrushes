<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovieGenre;
use Illuminate\Support\Facades\DB;

class MovieGenreTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $genres = [
            "terror",
            "romance",
            "acción",
            "comedia"
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MovieGenre::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        foreach ($genres as &$genre) {
            MovieGenre::create(['name' => $genre,]);
        }
    }
}
