<?php

namespace Database\Seeders;

use App\Models\Config\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'IT',
        ]);

        $departments = ['Purchasing', 'HR & GA', 'PPIC', 'Accounting', 'Operations', 'Sales', 'Marketing'];

        foreach ($departments as $name) {
            Department::create([
                'name' => $name,
            ]);
        }
    }
}
