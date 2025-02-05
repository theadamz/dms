<?php

namespace Database\Seeders;

use App\Models\Basic\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();

        $categories = ['Dokumen Legal', 'Dokumen Keuangan', 'Dokumen Sumber Daya Manusia', 'Dokumen Operasional', 'Dokumen Pemasaran dan Penjualan'];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
