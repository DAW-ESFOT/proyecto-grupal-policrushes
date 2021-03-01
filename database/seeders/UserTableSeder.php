<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Faker\Factory;

class UserTableSeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::query()->delete();

        $faker = Factory::create();

        $image_name = $faker->image('public/storage/images', 400, 300, NULL, FALSE);

        $password = Hash::make('123123');

        $pets = array('cats', 'dogs', 'rabbits', 'birds', 'peces');

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name'             => $faker->name($i < 5 ? 'male' : 'female'),
                'email'            => $faker->email,
                'image'            => "public/images/$image_name",
                'password'         => $password,
                'gender'           => $i < 5 ? 'male' : 'female',
                'preferred_gender' => $i < 5 ? 'female' : 'male',
                'age'              => rand(18, 40),
                'address'          => $faker->name,
                'min_age'          => rand(18, 23),
                'max_age'          => rand(23, 40),
                'lng'              => -0.219254,
                'lat'              => 78.484758,
                'preferred_pet'    => $pets[array_rand($pets)],
                'birthdate'        => date('Y-m-d H:i:s', strtotime('23-5-2000 23:15:23'))
            ]);

            $user->attachMusicGenres(['rock','pop','chicha','country']);
            $user->attachMovieGenres(['terror','comedia']);


        }
    }
}
