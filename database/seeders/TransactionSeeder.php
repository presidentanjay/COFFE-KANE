<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $products = Product::all();
        $userIds = User::pluck('id');

        // Buat 365 transaksi acak selama setahun
        for ($i = 0; $i < 365; $i++) {
            $transactionDate = Carbon::now()->subDays(rand(0, 364)); // acak tanggal 1 tahun terakhir

            $transaction = Transaction::create([
                'user_id' => $userIds->random(),
                'total' => 0, // nanti diupdate setelah tahu total detail
                'total_price' => 0,
                'status' => 'confirmed',
                'discount_amount' => 0,
                'customer_name' => $faker->name,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate,
            ]);

            $total = 0;

            $randomProducts = $products->random(rand(2, 5));
            foreach ($randomProducts as $product) {
                $quantity = rand(1, 5);
                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                ]);
            }

            $transaction->update([
                'total_price' => $total,
                'total' => $total,
            ]);
        }
    }
}
