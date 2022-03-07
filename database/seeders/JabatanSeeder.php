<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jabatan::create([
            "nama_jabatan" => "Guru Besar",
        ]);
        Jabatan::create([
            "nama_jabatan" => "Lektor Kepala",
        ]);
        Jabatan::create([
            "nama_jabatan" => "Lektor",
        ]);
        Jabatan::create([
            "nama_jabatan" => "Asisten Ahli",
        ]);
        Jabatan::create([
            "nama_jabatan" => "Tenaga Pengajar",
        ]);
    }
}
