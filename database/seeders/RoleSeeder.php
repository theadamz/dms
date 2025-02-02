<?php

namespace Database\Seeders;

use App\Models\Config\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => '00000000-0000-0000-0000-000000000000',
            'code' => 'DEV',
            'name' => 'Developer',
            'def_path' => '/configs/users'
        ]);

        Role::create([
            'id' => '00000000-0000-0000-0000-000000000001',
            'code' => 'ADMSTR',
            'name' => 'Administrator',
            'def_path' => '/configs/users'
        ]);

        Role::create([
            'code' => 'ADMIN',
            'name' => 'Admin',
            'def_path' => '/basics/categories'
        ]);

        Role::create([
            'code' => 'USR',
            'name' => 'User',
            'def_path' => '/basics/categories'
        ]);
    }
}
