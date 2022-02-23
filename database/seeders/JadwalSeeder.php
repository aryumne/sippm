<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jadwal::create([
            'nama_jadwal' => 'Pengusulan Proposal',
            'slug_jadwal' => 'pengusulan_proposal',
        ]);
        Jadwal::create([
            'nama_jadwal' => 'Upload Laporan Kemajuan',
            'slug_jadwal' => 'upload_laporan_kemajuan',
        ]);
        Jadwal::create([
            'nama_jadwal' => 'Upload Laporan Akhir',
            'slug_jadwal' => 'upload_laporan_akhir',
        ]);
        Jadwal::create([
            'nama_jadwal' => 'Penilaian Proposal',
            'slug_jadwal' => 'penilaian_proposal',
        ]);
        Jadwal::create([
            'nama_jadwal' => 'Monev Laporan Kemajuan',
            'slug_jadwal' => 'monev_laporan_kemajuan',
        ]);
    }
}
