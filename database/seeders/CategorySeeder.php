<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Menambahkan kategori
        DB::table('categories')->insert([
            ['name' => 'Coffee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non Coffee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manual Brew', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snack', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Main Course', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rice Bowl', 'created_at' => now(), 'updated_at' => now()],  // Menambahkan kategori Rice Bowl
        ]);
    }
}
