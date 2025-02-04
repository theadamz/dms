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
            'code' => 'dev',
            'name' => 'Developer',
            'def_path' => '/configs/users'
        ]);

        Role::create([
            'id' => '00000000-0000-0000-0000-000000000001',
            'code' => 'administrator',
            'name' => 'Administrator',
            'def_path' => '/configs/users'
        ]);

        Role::create([
            'code' => 'manager',
            'name' => 'Manager',
            'def_path' => '/my-approvals/waiting'
        ]);

        Role::create([
            'code' => 'staff',
            'name' => 'Staff',
            'def_path' => '/my-documents/list'
        ]);
    }
}
