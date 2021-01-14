<?php

namespace Database\Seeders;

use App\Models\MusicGender;
use Illuminate\Database\Seeder;

class MusicGenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = [
            "rock",
            "urbano",
            "punk",
            "electronica",
        ];
        // Vaciar la tabla.
        MusicGender::truncate();
        $faker = \Faker\Factory::create();
        // Crear artículos ficticios en la tabla 
        /*
        for ($i = 0; $i < 3; $i++) {
            MusicGender::create([
                'name' => $faker->word
            ]);
        }*/

        foreach ($genders as &$gender) {
            MusicGender::create(['name' => $gender,]);
        }
    }
}
