<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(UserTableSeder::class);
        $this->call(ChatTableSeeder::class);
        $this->call(MessageTableSeeder::class);
        $this->call(MovieGenderTableSeeder::class);
        $this->call(MusicGenderTableSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}
