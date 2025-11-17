<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');

        // Statistik utama
        $totalRevenue = Transaction::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();

        $successTransactions = Transaction::where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $pendingTransactions = Transaction::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Top 5 Produk Terlaris
        $topProductsRaw = TransactionDetail::select(
                'products.name',
                DB::raw('SUM(transaction_details.quantity) as total_quantity')
            )
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'confirmed')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $topProducts = $topProductsRaw->pluck('total_quantity', 'name')->toArray();

        // Distribusi Pendapatan per Produk
        $revenueChartRaw = TransactionDetail::select(
                'products.name',
                DB::raw('SUM(transaction_details.quantity * transaction_details.price) as revenue')
            )
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'confirmed')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->get();

        $revenueChart = $revenueChartRaw->pluck('revenue', 'name')->toArray();

        // Grafik Harian
        $dailySales = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $dates = $dailySales->pluck('date')->toArray();
        $totals = $dailySales->pluck('total')->toArray();

        // Grafik Mingguan
        $weeklySales = Transaction::select(
                DB::raw('YEARWEEK(created_at, 1) as week'),
                DB::raw('MIN(DATE(created_at)) as week_start'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('week')
            ->get();

        $weeklyLabels = $weeklySales->map(fn($w) => date('d M', strtotime($w->week_start)) . ' (Minggu ' . substr($w->week, -2) . ')')->toArray();
        $weeklyTotals = $weeklySales->pluck('total')->toArray();

        // Grafik Bulanan
        $monthlySales = Transaction::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('month')
            ->get();

        $monthlyLabels = $monthlySales->pluck('month')->map(fn($m) => date('F Y', strtotime($m . '-01')))->toArray();
        $monthlyTotals = $monthlySales->pluck('total')->toArray();

        return view('admin.reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'successTransactions' => $successTransactions,
            'pendingTransactions' => $pendingTransactions,
            'topProducts' => $topProducts,
            'revenueChart' => $revenueChart,
            'dates' => $dates,
            'totals' => $totals,
            'weeklyLabels' => $weeklyLabels,
            'weeklyTotals' => $weeklyTotals,
            'monthlyLabels' => $monthlyLabels,
            'monthlyTotals' => $monthlyTotals,
        ]);
    }

    public function export(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal tidak valid untuk export!');
        }

        return Excel::download(
            new SalesExport($startDate, $endDate),
            "laporan_penjualan_{$startDate}_to_{$endDate}.xlsx"
        );
    }
}
