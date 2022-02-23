<?php

namespace Database\Seeders;

use App\Models\SumberDana;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SumberDana::create([
            "sumber" => "MANDIRI",
        ]);

        SumberDana::create([
            "sumber" => "PEMDA",
        ]);

        SumberDana::create([
            "sumber" => "SWASTA",
        ]);

        SumberDana::create([
            "sumber" => "NGO",
        ]);

        SumberDana::create([
            "sumber" => "UNIPA",
        ]);
    }
}
