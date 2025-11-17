<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;

class KasirController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalIncome = Transaction::whereDate('created_at', $today)->sum('total_price');
       $totalItemsSold = TransactionDetail::whereDate('created_at', $today)->sum('quantity');

        $recentTransactions = Transaction::latest()->take(5)->get();
        $products = Product::where('stock', '>', 0)->get();

        return view('kasir.dashboard', [
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalIncome,
            'totalItemsSold' => $totalItemsSold,
            'recentTransactions' => $recentTransactions,
            'products' => $products,
        ]);
    }

    public function createManual()
    {
        $products = Product::all();
        return view('kasir.transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $items = json_decode($request->items, true); // [{id: 1, qty: 2}, ...]

        if (!$items || count($items) === 0) {
            return redirect()->back()->with('error', 'Tidak ada item dalam pesanan.');
        }

        $total = 0;

        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $total += $product->price * $item['qty'];
            }
        }

        // ✅ Simpan transaksi utama, termasuk user_id kasir yang sedang login
        $transaction = Transaction::create([
            'user_id' => auth()->id(), // <- perbaikan penting
            'total_price' => $total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Simpan detail transaksi
        foreach ($items as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
            ]);
        }

        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function transactions()
{
    $transactions = \App\Models\Transaction::where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('kasir.transactions.index', compact('transactions'));
}
public function report()
{
    $today = now()->toDateString();

    $totalTransactions = \App\Models\Transaction::where('user_id', auth()->id())
        ->whereDate('created_at', $today)
        ->count();

    $totalIncome = \App\Models\Transaction::where('user_id', auth()->id())
        ->whereDate('created_at', $today)
        ->sum('total_price');

    $totalItemsSold = \App\Models\TransactionDetail::whereDate('created_at', $today)
        ->sum('quantity'); // pastikan kolom ini sudah ada

    return view('kasir.reports.index', compact(
        'totalTransactions', 'totalIncome', 'totalItemsSold'
    ));
}

}
