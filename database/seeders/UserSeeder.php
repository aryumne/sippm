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
            'nidn' => '0000000000',
            'email' => 'dev.tik@unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('12341234'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => '0000000000',
            'nama' => 'DEV TIK UNIPA',
            'jabatan_id' => 1,
            'prodi_id' => 1,
            'handphone' => '081234567890',
            'email' => 'dev.tik@unipa.ac.id',
        ]);
    }
}
