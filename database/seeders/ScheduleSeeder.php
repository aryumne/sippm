<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schedule::create([
            'started_at' => now(),
            'finished_at' => now(),
            'jadwal_id' => 1,
            'user_id' => 3,
        ]);
        Schedule::create([
            'started_at' => now(),
            'finished_at' => now(),
            'jadwal_id' => 2,
            'user_id' => 3,
        ]);
        Schedule::create([
            'started_at' => now(),
            'finished_at' => now(),
            'jadwal_id' => 3,
            'user_id' => 3,
        ]);
        Schedule::create([
            'started_at' => now(),
            'finished_at' => now(),
            'jadwal_id' => 4,
            'user_id' => 3,
        ]);
        Schedule::create([
            'started_at' => now(),
            'finished_at' => now(),
            'jadwal_id' => 5,
            'user_id' => 3,
        ]);
    }
}
