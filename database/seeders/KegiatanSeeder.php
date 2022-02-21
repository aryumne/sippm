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
            "sumber" => "Mandiri"
        ]);

        SumberDana::create([
            "sumber" => "PEMDA"
        ]);

        SumberDana::create([
            "sumber" => "Swasta"
        ]);

        SumberDana::create([
            "sumber" => "NJO"
        ]);

        SumberDana::create([
            "sumber" => "UNIPA"
        ]);
    }
}
