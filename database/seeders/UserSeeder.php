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
        //Admin DEV TIK
        User::create([
            'nidn' => 'DVTIKADMIN',
            'email' => 'dev.tik@unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('l4r4v3ldev'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => 'DVTIKADMIN',
            'nama' => 'DEV TIK UNIPA',
            'jabatan_id' => 4,
            'prodi_id' => 1,
            'email' => 'dev.tik@unipa.ac.id',
        ]);

        //Kepala Admin LPPM
        User::create([
            'nidn' => 'KADMINLPPM',
            'email' => 'lp2m@unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('lp2mKAdmin'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => 'KADMINLPPM',
            'nama' => 'kEPALA ADMIN LPPM',
            'jabatan_id' => 1,
            'prodi_id' => 1,
            'email' => 'lp2m@unipa.ac.id',
        ]);

        //Admin 1 LPPM
        User::create([
            'nidn' => 'ADMINLPPM1',
            'email' => 'lp2m1@unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('lp2mAdmin1'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => 'ADMINLPPM1',
            'nama' => 'ADMIN 1 LPPM',
            'jabatan_id' => 1,
            'prodi_id' => 1,
            'email' => 'lp2m1@unipa.ac.id',
        ]);

        //Admin 2 LPPM
        User::create([
            'nidn' => 'ADMINLPPM2',
            'email' => 'lp2m2@unipa.ac.id',
            'email_verified_at' => now(),
            'password' => Hash::make('lp2mAdmin2'),
            'role_id' => 1,
        ]);

        Dosen::create([
            'nidn' => 'ADMINLPPM2',
            'nama' => 'ADMIN 2 LPPM',
            'jabatan_id' => 1,
            'prodi_id' => 1,
            'email' => 'lp2m2@unipa.ac.id',
        ]);

    }
}
