<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\ProdiSeeder;
use Database\Seeders\JadwalSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\JenisJHSeeder;
use Database\Seeders\KegiatanSeeder;
use Database\Seeders\ScheduleSeeder;
use Database\Seeders\PeruntukanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // RoleSeeder::class,
            // JabatanSeeder::class,
            // JenisJHSeeder::class,
            // KegiatanSeeder::class,
            // JadwalSeeder::class,
            // ScheduleSeeder::class,
            // FacultySeeder::class,
            // ProdiSeeder::class,
            // UserSeeder::class,
            PeruntukanSeeder::class
        ]);
    }
}
