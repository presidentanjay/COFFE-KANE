<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::select('id', 'user_id', 'total_price', 'status', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['ID', 'User ID', 'Total Harga', 'Status', 'Tanggal Transaksi'];
    }
}
