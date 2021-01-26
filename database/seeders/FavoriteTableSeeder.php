<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla.
        Favorite::truncate();
        $faker = \Faker\Factory::create();
        // Crear artículos ficticios en la tabla

       // factory(App\Models\User::class, 2)->create()->each(function ($user) {
        //    $user->users()->saveMany(factory(App\Models\User::class, 25)->make());
        //});
    }
}
