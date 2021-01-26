<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciamos la tabla chats
        Chat::truncate();
        $faker = \Faker\Factory::create();

        //Obtenemos la lista de todos los usuarios creados e
        //iteramos sobre cada uno y simulamos un inicio de
        //sesión con cada uno para crear chats en su nombre
        $users = User::all();
        foreach ($users as $user) {
            // iniciamos sesión con este usuario
            JWTAuth::attempt([
                'email' => $user->email,
                'password' => '123123'
            ]);

            // Y ahora con este usuario creamos algunos chats
            $num_chats = 10;
            for ($j = 0; $j < $num_chats; $j++) {
                Chat::create([
                ]);
            }
        }
    }
}

