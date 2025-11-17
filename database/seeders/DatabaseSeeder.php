<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            SalesDataSeeder::class,
            ImportTransactionsSeeder::class,
            AssociationRuleInteractionSeeder::class,
            
        ]);
    }
}
