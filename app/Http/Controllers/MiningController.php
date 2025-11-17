<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\AprioriService;

class MiningController extends Controller
{
    public function runMining()
    {
        $transactions = Transaction::with('items.product')->get();

        $data = [];
        foreach ($transactions as $transaction) {
            $items = [];
            foreach ($transaction->items as $item) {
                $items[] = $item->product->name;
            }
            $data[] = $items;
        }

        $apriori = new AprioriService($data, 0.3); // minSupport 30%
        $frequentItems = $apriori->run();

        return response()->json($frequentItems);
    }
}
