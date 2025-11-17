<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'confirmed')
            ->whereBetween('transactions.created_at', [$this->startDate, $this->endDate])
            ->select(
                'products.name as Produk',
                DB::raw('SUM(transaction_items.quantity) as Jumlah'),
                DB::raw('SUM(transaction_items.price * transaction_items.quantity) as Total')
            )
            ->groupBy('products.name')
            ->get();
    }
}
