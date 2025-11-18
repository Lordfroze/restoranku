<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item; // Menggunakan model Item untuk mengakses database



class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // membuat data seeder dari factory ItemFactory
        Item::factory(10)->create(); // Membuat 10 data item menggunakan factory ItemFactory
    }
}
