<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MovieGender;

class MovieGenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = [
            "terror",
            "romance",
            "acción",
        ];
        // Vaciar la tabla.
        MovieGender::truncate();
        $faker = \Faker\Factory::create();
        // Crear artículos ficticios en la tabla 

        foreach ($genders as &$gender) {
            MovieGender::create(['name' => $gender,]);
        }
    }
}
