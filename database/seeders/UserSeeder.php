<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nidn' => '1234567890',
            'email' => 'm.sapari@student.unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('12341234'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => '1234567890',
            'nama' => 'Mozes Sapari',
            'jabatan_id' => 1,
            'prodi_id' => 1,
            'handphone' => '081234567890',
            'email' => 'm.sapari@student.unipa.ac.id',
        ]);
    }
}
