<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $range = $request->get('range', 'monthly');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        // Format label grafik
        $format = match ($range) {
            'daily'   => '%Y-%m-%d',
            'weekly'  => '%x-W%v',
            'monthly' => '%Y-%m',
            default   => '%Y-%m'
        };

        // Query dasar transaksi (status confirmed)
        $transactionQuery = Transaction::where('status', 'confirmed');

        if ($startDate && $endDate) {
            $transactionQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Grafik Penjualan
        $salesRaw = $transactionQuery->clone()
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $salesChart = [
            'labels' => $salesRaw->pluck('label'),
            'data'   => $salesRaw->pluck('total'),
        ];

        // 5 Produk Terlaris
        $topProducts = Product::select('products.name', DB::raw('SUM(transaction_details.quantity) as total_sold'))
            ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'confirmed')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Produk stok menipis
        $lowStockProducts = Product::where('stock', '<', 10)->get();

        // Transaksi terbaru
        $latestTransactions = Transaction::with('user')
            ->where('status', 'confirmed')
            ->when($startDate && $endDate, fn($q) => $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']))
            ->latest()
            ->limit(10)
            ->get();

        // Statistik ringkas
        $totalProduk     = Product::count();
        $totalTransaksi  = $transactionQuery->clone()->count();
        $totalPendapatan = $transactionQuery->clone()->sum('total_price');
        $totalPengguna   = User::count();

        return view('admin.dashboard', [
            'salesChart'         => $salesChart,
            'topProducts'        => $topProducts,
            'lowStockProducts'   => $lowStockProducts,
            'latestTransactions' => $latestTransactions,
            'totalProduk'        => $totalProduk,
            'totalTransaksi'     => $totalTransaksi,
            'totalPendapatan'    => $totalPendapatan,
            'totalPengguna'      => $totalPengguna,
            'range'              => $range,
            'startDate'          => $startDate,
            'endDate'            => $endDate,
        ]);
    }
}
