<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\AssociationRule;
use App\Services\AprioriService;

class RunAprioriMining extends Command
{
    protected $signature = 'mining:apriori {min_support=0.3}';

    protected $description = 'Run Apriori mining from transaction data';

    public function handle()
    {
        $minSupport = (float) $this->argument('min_support');

        $this->info("Mengambil data transaksi...");

        $transactions = Transaction::with('items.product')->get();

        $data = [];
        foreach ($transactions as $transaction) {
            $items = [];
            foreach ($transaction->items as $item) {
                $items[] = $item->product->name;
            }
            $data[] = $items;
        }

        $this->info("Menjalankan algoritma Apriori dengan min support $minSupport ...");

        $apriori = new AprioriService($data, $minSupport);
        $frequentItems = $apriori->run();

        $this->info("Simpan aturan asosiasi ke database...");

        foreach ($frequentItems as $item => $support) {
            AssociationRule::updateOrCreate(
                ['rule' => $item],
                [
                    'support' => $support,
                    'confidence' => 1.0,  // default karena ini single item
                    'lift' => null,
                    'is_active' => true,
                ]
            );
        }

        $this->info("Mining selesai, ".count($frequentItems)." aturan disimpan.");

        return 0;
    }
}
