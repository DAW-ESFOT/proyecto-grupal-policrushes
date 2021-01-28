<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciamos la tabla messages
        Message::truncate();
        $faker = \Faker\Factory::create();
        // Obtenemos todos los chats de la bdd
        $chats = App\Models\Chat::all();
        // Obtenemos todos los usuarios
        $users = App\Models\User::all();
        foreach ($users as $user) {
            // iniciamos sesión con cada uno
            JWTAuth::attempt([
                'email' => $user->email,
                'password' => '123123'
            ]);
            // Creamos un mensaje para cada chat con este usuario
            foreach ($chats as $chat) {
                Message::create([
                    'date' => $faker->dateTime,
                    'seen' => $faker->boolean,
                    'content' => $faker->text,
                    'chat_id' => $chat->id,
                ]);
            }
        }
    }
}
