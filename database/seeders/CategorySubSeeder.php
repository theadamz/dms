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
        CategorySub::truncate();

        $categories = ['Dokumen Legal', 'Dokumen Keuangan', 'Dokumen Sumber Daya Manusia', 'Dokumen Operasional', 'Dokumen Pemasaran dan Penjualan'];
        $data = [
            ['Kontrak dan Perjanjian', 'Dokumen Pendaftaran Perusahaan', 'Kebijakan Privasi', 'Perizinan dan Lisensi'],
            ['Laporan Keuangan', 'Anggaran dan Proyeksi', 'Faktur dan Kwitansi', 'Dokumen Pajak'],
            ['Kontrak Karyawan', 'Kebijakan SDM', 'Rekrutmen dan Seleksi', 'Pelatihan dan Pengembangan'],
            ['Prosedur Operasional Standar (SOP)', 'Manual Karyawan', 'Laporan Kinerja', 'Rencana Kontinjensi'],
            ['Rencana Pemasaran', 'Materi Promosi', 'Laporan Penjualan', 'Analisis Pasar']
        ];

        foreach ($categories as $index => $category) {
            $categoryId = Category::where('name', $category)->value('id');

            foreach ($data[$index] as $value) {
                CategorySub::create([
                    'category_id' => $categoryId,
                    'name' => $value,
                ]);
            }
        }
    }
}
