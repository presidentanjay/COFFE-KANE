<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\AssociationRule;
use Illuminate\Support\Facades\DB;

class GenerateLowSalePromos extends Command
{
    protected $signature = 'promo:generate-low-sale {threshold=5}';

    protected $description = 'Generate promo bundling for low selling products';

    public function handle()
    {
        $threshold = (int) $this->argument('threshold');

        $this->info("Menghitung produk dengan penjualan kurang dari $threshold ...");

        // Hitung penjualan produk selama 30 hari terakhir
        $sales = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->select('transaction_items.product_id', DB::raw('SUM(transaction_items.quantity) as total_sold'))
            ->where('transactions.status', 'confirmed')
            ->where('transactions.created_at', '>=', now()->subDays(30))
            ->groupBy('transaction_items.product_id')
            ->get();

        // Produk yang penjualannya kurang dari threshold
        $lowSaleProductIds = $sales->filter(function ($item) use ($threshold) {
            return $item->total_sold < $threshold;
        })->pluck('product_id')->toArray();

        // Produk yang belum terjual sama sekali
        $allProductIds = Product::pluck('id')->toArray();
        $noSaleProductIds = array_diff($allProductIds, $sales->pluck('product_id')->toArray());

        $lowSaleProductIds = array_merge($lowSaleProductIds, $noSaleProductIds);

        if (empty($lowSaleProductIds)) {
            $this->info('Tidak ada produk dengan penjualan rendah.');
            return 0;
        }

        $products = Product::whereIn('id', $lowSaleProductIds)->get();

        $this->info('Produk kurang laku:');
        foreach ($products as $product) {
            $this->line("- {$product->name}");
        }

        // Buat aturan bundling promo (contoh bundling 2 produk)
        // Simplifikasi: buat rule "Beli Produk X + Produk Y dapat diskon"
        // Contoh generate semua kombinasi 2 produk kurang laku

        $rulesCreated = 0;

        foreach ($products as $key => $prodA) {
            foreach ($products as $index => $prodB) {
                if ($key >= $index) continue; // hindari duplikat dan self pairing

                $ruleText = "{$prodA->name} & {$prodB->name} => Promo Diskon";

                AssociationRule::updateOrCreate(
                    ['rule' => $ruleText],
                    [
                        'support' => 0,      // belum dihitung
                        'confidence' => 0,
                        'lift' => 0,
                        'is_active' => true,
                    ]
                );

                $rulesCreated++;
            }
        }

        $this->info("Selesai membuat $rulesCreated aturan bundling produk kurang laku.");

        return 0;
    }
}
