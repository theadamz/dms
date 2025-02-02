<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => '00000000-0000-0000-0000-000000000000',
            'email' => 'theadamz91@gmail.com',
            'username' => 'dev',
            'name' => 'Developer',
            'password' => '12345678',
            'role_id' => '00000000-0000-0000-0000-000000000000',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        User::create([
            'id' => '00000000-0000-0000-0000-000000000001',
            'username' => 'administrator',
            'email' => 'administrator@email.com',
            'name' => 'Administrator',
            'password' => '12345678',
            'role_id' => '00000000-0000-0000-0000-000000000001',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
    }
}
