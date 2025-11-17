<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $filter     = $request->input('filter');
        $search     = $request->input('search');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $query = Transaction::with('user', 'items.product');

        // Default sort: terbaru ke lama
        $sort = 'desc';

        // 🔘 Filter status atau sort
        if ($filter) {
            if (str_starts_with($filter, 'status:')) {
                $status = str_replace('status:', '', $filter);
                $query->where('status', $status);
            } elseif (str_starts_with($filter, 'sort:')) {
                $sort = str_replace('sort:', '', $filter);
            }
        }

        // 📅 Filter tanggal
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // 🔍 Search by ID atau nama customer
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%$search%");
                  });
            });
        }

        // Eksekusi & urutkan
        $transactions = $query->orderBy('id', $sort)->paginate(10);

        // Fallback nama customer
        foreach ($transactions as $transaction) {
            $transaction->customer_name = $transaction->customer_name ?? $transaction->user->name;
        }

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items.product');
        $transaction->customer_name = $transaction->customer_name ?? $transaction->user->name;

        return view('admin.transactions.show', compact('transaction'));
    }

    public function confirm(Transaction $transaction)
    {
        if ($transaction->status === 'confirmed') {
            return back()->with('info', 'Transaksi sudah dikonfirmasi.');
        }

        foreach ($transaction->items as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        $transaction->update(['status' => 'confirmed']);
        Mail::to($transaction->user->email)->send(new OrderStatusMail($transaction));

        return back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,completed,cancelled',
        ]);

        if ($transaction->status === $request->input('status')) {
            return back()->with('info', 'Status transaksi sudah sesuai.');
        }

        $transaction->update([
            'status' => $request->input('status'),
        ]);

        if ($request->input('status') === 'confirmed') {
            Mail::to($transaction->user->email)->send(new OrderStatusMail($transaction));
        }

        return redirect()->route('admin.transactions.show', $transaction->id)
                         ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->items()->delete();
        $transaction->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->customer_name = $transaction->customer_name ?? $transaction->user->name;

        return view('admin.transactions.edit', compact('transaction'));
    }
}
