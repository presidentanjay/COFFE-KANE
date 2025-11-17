<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Coffee
        DB::table('products')->insert([
            ['name' => 'COFFEE KANE', 'category_id' => 1, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COFFEE SUSU', 'category_id' => 1, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COFFEE SUSU GULA AREN', 'category_id' => 1, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COFFEE SUSU CARAMEL', 'category_id' => 1, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'AMERICANO', 'category_id' => 1, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CAFE LATE', 'category_id' => 1, 'price' => 22000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CAPPUCINO', 'category_id' => 1, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Non-Coffee
        DB::table('products')->insert([
            ['name' => 'CHOCO MILK', 'category_id' => 2, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MATCHA', 'category_id' => 2, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'RED VELVET', 'category_id' => 2, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LEMON TEA', 'category_id' => 2, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LECY TEA', 'category_id' => 2, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CO’ SUN', 'category_id' => 2, 'price' => 22000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'HAPPY SODA', 'category_id' => 2, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'KOCOK', 'category_id' => 2, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ICE COFFEE LEMONADE', 'category_id' => 2, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'KANE TEA', 'category_id' => 2, 'price' => 5000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Manual Brew
        DB::table('products')->insert([
            ['name' => 'V60', 'category_id' => 3, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'JAPANESE', 'category_id' => 3, 'price' => 24000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'VIETNAM DRIP', 'category_id' => 3, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'VIETNAM BLACK', 'category_id' => 3, 'price' => 19000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'INDONESIAN TUBRUK', 'category_id' => 3, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Snack
        DB::table('products')->insert([
            ['name' => 'TAHU CABE GARAM', 'category_id' => 4, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'PISANG GULA AREN', 'category_id' => 4, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'FRENCH FRIES', 'category_id' => 4, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CIRENG BUMBU RUJAK', 'category_id' => 4, 'price' => 15000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Main Course
        DB::table('products')->insert([
            ['name' => 'NASI GORENG KANE', 'category_id' => 5, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CHICKEN WING', 'category_id' => 5, 'price' => 20000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SAUSAGE', 'category_id' => 5, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'NUGGET', 'category_id' => 5, 'price' => 18000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'INDOMIE', 'category_id' => 5, 'price' => 10000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'INDOMIE EGG', 'category_id' => 5, 'price' => 12000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'FRIED INDOMIE', 'category_id' => 5, 'price' => 10000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'FRIED EGG INDOMIE', 'category_id' => 5, 'price' => 12000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Rice Bowl
        DB::table('products')->insert([
            ['name' => 'CHICKEN KATSU BBQ', 'category_id' => 6, 'price' => 25000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CHICKEN KATSU BLACK PEPPER', 'category_id' => 6, 'price' => 25000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CRISPY CHICKEN BBQ', 'category_id' => 6, 'price' => 25000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'CRISPY CHICKEN BLACK PEPPER', 'category_id' => 6, 'price' => 25000, 'stock' => 1000, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
