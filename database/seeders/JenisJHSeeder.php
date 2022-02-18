<?php

namespace Database\Seeders;

use App\Models\Jenis_hki;
use App\Models\Jenis_jurnal;
use Illuminate\Database\Seeder;

class JenisJHSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Jenis Haki
        Jenis_hki::create([
            "hki" => "Hak Cipta",
        ]);
        Jenis_hki::create([
            "hki" => "Hak Paten",
        ]);
        Jenis_hki::create([
            "hki" => "Indikasi Biografi",
        ]);
        Jenis_hki::create([
            "hki" => "Hak Merek",
        ]);
        Jenis_hki::create([
            "hki" => "Desain Industri",
        ]);
        Jenis_hki::create([
            "hki" => "Desain Tata Letak Sirkuit Terpadu",
        ]);
        Jenis_hki::create([
            "hki" => "Rahasia Dagang",
        ]);
        Jenis_hki::create([
            "hki" => "Perlindungan Varietas Tanaman",
        ]);

        //Jenis Jurnal
        Jenis_jurnal::create([
            "jurnal" => "Prosiding Internasional"
        ]);
        Jenis_jurnal::create([
            "jurnal" => "Internasional"
        ]);
        Jenis_jurnal::create([
            "jurnal" => "Nasional Terakreditasi"
        ]);
        Jenis_jurnal::create([
            "jurnal" => "Nasional"
        ]);
    }
}
