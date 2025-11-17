<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\User;

class ImportTransactionsSeeder extends Seeder
{
    public function run(): void
    {
        // Menambahkan pengguna dummy jika tabel 'users' kosong
        if (User::count() == 0) {
            User::create(['name' => 'User 1', 'email' => 'user1@example.com', 'password' => bcrypt('password')]);
            User::create(['name' => 'User 2', 'email' => 'user2@example.com', 'password' => bcrypt('password')]);
            User::create(['name' => 'User 3', 'email' => 'user3@example.com', 'password' => bcrypt('password')]);
        }

        // Data dummy produk
        $products = [
            ['name' => 'Coffee Susu Caramel', 'price' => 24000],
            ['name' => 'Americano', 'price' => 18000],
            ['name' => 'Latte', 'price' => 22000],
            ['name' => 'Espresso', 'price' => 20000]
        ];

        // Menambahkan produk ke database
        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                ['price' => $productData['price']]
            );
        }

        // Mendapatkan user_ids acak untuk transaksi
        $userIds = User::all()->pluck('id')->toArray(); // Ambil semua user_id yang ada

        // Menambahkan data transaksi dummy
        $transactions = [
            ['total' => 72000, 'created_at' => Carbon::now()->subDays(1)],
            ['total' => 56000, 'created_at' => Carbon::now()->subDays(2)],
            ['total' => 46000, 'created_at' => Carbon::now()->subDays(3)],
            ['total' => 88000, 'created_at' => Carbon::now()->subDays(4)],
        ];

        foreach ($transactions as $transactionData) {
            // Insert transaksi dan ambil transaction_id dengan user_id acak
            $transaction_id = DB::table('transactions')->insertGetId([
                'user_id' => $userIds[array_rand($userIds)], // Ambil user_id acak
                'created_at' => $transactionData['created_at'],
                'total' => $transactionData['total'],
                'total_price' => $transactionData['total'], // tambahkan ini
            ]);

            // Ambil produk yang sudah ada dan hubungkan dengan transaksi
            $productIds = Product::all()->pluck('id')->toArray();

            foreach ($productIds as $productId) {
                // Ambil harga produk untuk setiap transaksi
                $productPrice = Product::find($productId)->price;

                // Randomize quantity (jumlah produk antara 1 hingga 3)
                $quantity = rand(1, 3);

                // Menambahkan ke tabel transaction_details
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transaction_id,
                    'product_id' => $productId,
                    'price' => $productPrice,  // Menambahkan harga produk yang sesuai
                    'quantity' => $quantity,  // Menambahkan quantity acak
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
