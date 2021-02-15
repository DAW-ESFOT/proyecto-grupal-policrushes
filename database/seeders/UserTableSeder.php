<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class UserTableSeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Factory::create();

        $image_name = $faker->image('public/storage/images', 400, 300, NULL, FALSE);

        $password = Hash::make('123123');

        $pets = array('cats', 'dogs', 'rabbits', 'birds', 'peces');

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name'             => $faker->name($i < 5 ? 'male' : 'female'),
                'email'            => $faker->email,
                'image'            => $image_name,
                'password'         => $password,
                'gender'           => $i < 5 ? 'male' : 'female',
                'preferred_gender' => $i < 5 ? 'female' : 'male',
                'age'              => rand(18, 40),
                'address'          => $faker->name,
                'min_age'          => rand(18, 23),
                'max_age'          => rand(23, 40),
                'location'          => DB::raw("(ST_GeomFromText('POINT(37.774929 -122.419415)'))"),
                'preferred_pet'    => $pets[array_rand($pets)],
            ]);
        }
    }
}
