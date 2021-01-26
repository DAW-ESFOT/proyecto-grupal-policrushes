<?php

namespace Database\Seeders;

use App\Models\MovieGender;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MusicGender;
use Illuminate\Support\Facades\Hash;

class UserTableSeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla
        User::truncate();
        $faker = \Faker\Factory::create();

        $image_name = $faker->image('public/storage/images', 400, 300, null, false);

        // Crear la misma clave para todos los usuarios
        // conviene hacerlo antes del for para que el seeder // no se vuelva lento.
        $password = Hash::make('123123');
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@prueba.com',
            'image' => 'users/' . $image_name,

            'password' => $password,
        ]);

        // Generar algunos usuarios para nuestra aplicacion for ($i = 0; $i < 10; $i++) {
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'image'=> $image_name,
                'password' => $password,
            ]);

            /*$user->music()->saveMany(
                $faker->randomElements(
                    array(
                        MusicGender::find(1),
                        MusicGender::find(2),
                        MusicGender::find(3),
                        MusicGender::find(4),
                    ),$faker->numberBetween(1, 3), false,
                    array(
                        MovieGender::find(1),
                        MovieGender::find(2),
                        MovieGender::find(3),
                        MovieGender::find(4),
                    ),$faker->numberBetween(1, 3), false),
            );

            $user->movie()->saveMany(
                array(
                        MovieGender::find(1),
                        MovieGender::find(2),
                        MovieGender::find(3),
                        MovieGender::find(4),
                    ),$faker->numberBetween(1, 3), false)
                $faker->randomElements(

            );*/
        }
    }
}