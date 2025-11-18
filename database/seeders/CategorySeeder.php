<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Menggunakan facade DB untuk mengakses database

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat data kategori
        $categories = [
            ['cat_name' => 'Makanan', 'description' => 'Kategori makanan'],
            ['cat_name' => 'Minuman', 'description' => 'Kategori minuman'],
        ];

        DB::table('categories')->insert($categories); // Memasukkan data kategori ke dalam tabel categories
    }
}
