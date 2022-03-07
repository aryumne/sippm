<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculty::create([
            'nama_faculty' => 'Matematika dan Ilmu Pengetahuan Alam',
        ]);
        Faculty::create([
            'nama_faculty' => 'Keguruan dan Ilmu Pendidikan',
        ]);
        Faculty::create([
            'nama_faculty' => 'Teknik',
        ]);
        Faculty::create([
            'nama_faculty' => 'Teknik Pertambangan dan Perminyakan',
        ]);
        Faculty::create([
            'nama_faculty' => 'Sastra dan Budaya',
        ]);
        Faculty::create([
            'nama_faculty' => 'Teknologi Pertanian',
        ]);
        Faculty::create([
            'nama_faculty' => 'Pertanian',
        ]);
        Faculty::create([
            'nama_faculty' => 'Perikanan dan Ilmu Kelautan',
        ]);
        Faculty::create([
            'nama_faculty' => 'Peternakan',
        ]);
        Faculty::create([
            'nama_faculty' => 'Kehutanan',
        ]);
        Faculty::create([
            'nama_faculty' => 'Ekonomi dan Bisnis',
        ]);
        Faculty::create([
            'nama_faculty' => 'Kedokteran',
        ]);
        Faculty::create([
            'nama_faculty' => 'Pasca Sarjana',
        ]);
    }
}
