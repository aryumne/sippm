<?php

namespace Database\Seeders;

use App\Models\Peruntukan;
use Illuminate\Database\Seeder;

class PeruntukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Peruntukan::create([
            'nama_peruntukan' => "Undang-undang"
        ]);
        Peruntukan::create([
            'nama_peruntukan' => "Peraturan Daerah"
        ]);
        Peruntukan::create([
            'nama_peruntukan' => "Peraturan Pemerintah"
        ]);
        Peruntukan::create([
            'nama_peruntukan' => "Peraturan Presiden"
        ]);
    }
}
