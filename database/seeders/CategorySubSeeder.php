<?php

namespace Database\Seeders;

use App\Models\Basic\Category;
use App\Models\Basic\CategorySub;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            CategorySub::create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'name' => fake()->word(),
            ]);
        }
    }
}
