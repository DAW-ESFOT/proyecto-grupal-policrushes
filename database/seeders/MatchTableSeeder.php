<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use App\Models\Match;
use Carbon\Carbon;

class MatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Match::truncate();
        $index = 12;

        for ($i = 1; $i <= 6; $i++) {

            DB::table('matches')->insert([
                [
                    'user2_id' => $i,
                    'user1_id' => $index,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
            ]);  
            $index--;      
        }

        $seed = [];
    }
}
