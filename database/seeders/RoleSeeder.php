<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "nama_role" => "Admin",
        ]);
        Role::create([
            "nama_role" => "Pengusul",
        ]);
        Role::create([
            "nama_role" => "Reviewer",
        ]);
    }
}
