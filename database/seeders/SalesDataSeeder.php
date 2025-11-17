<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesDataSeeder extends Seeder
{
    public function run()
    {
        // Insert data dummy atau data yang diinginkan
        for ($i = 0; $i < 30; $i++) {
            DB::table('sales_data')->insert([
                'date' => Carbon::now()->subDays($i)->toDateString(),
                'total_sales' => rand(100, 1000),
                'period_type' => 'daily',
            ]);
        }

        // Tambahkan data mingguan dan bulanan seperti sebelumnya
    }
}
