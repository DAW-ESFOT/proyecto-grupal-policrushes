<?php

namespace Database\Seeders;

use App\Models\MusicGenre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicGenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            "rock",
            "urban",
            "punk",
            "electro",
            "country"
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MusicGenre::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        foreach ($genres as &$genre) {
            MusicGenre::create(['name' => $genre,]);
        }
    }
}
