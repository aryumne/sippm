<?php

namespace Database\Seeders;

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
            'nama' => 'UPT TIK DEv',
            'email' => 'aryumsf@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12341234'),
            'jabatan_id' => 1,
            'role_id' => 1,
        ]);
    }
}
