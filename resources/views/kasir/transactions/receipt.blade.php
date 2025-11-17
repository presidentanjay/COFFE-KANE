@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 shadow-lg rounded-2xl mt-10 border border-gray-200">
    <h1 class="text-3xl font-bold text-center mb-6 text-indigo-700">🧾 Struk Transaksi</h1>

    <div class="text-sm text-gray-700 space-y-1 mb-6 border-b pb-4">
        <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
        <p><strong>Kasir:</strong> {{ $transaction->user->name }}</p>
    </div>

    <table class="w-full table-auto text-sm mb-4 border">
        <thead class="bg-gray-100 text-gray-700">
            <tr class="border-b">
                <th class="text-left px-3 py-2">Produk</th>
                <th class="text-center px-3 py-2">Qty</th>
                <th class="text-right px-3 py-2">Harga</th>
                <th class="text-right px-3 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $item->product->name }}</td>
                    <td class="px-3 py-2 text-center">{{ $item->quantity }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right text-lg font-bold text-gray-800 mt-4">
        Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-center items-center gap-4">
        <a href="{{ route('kasir.transactions.index') }}"
           class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
            ⬅ Kembali
        </a>

        <a href="{{ route('kasir.transactions.receipt.pdf', $transaction) }}"
           target="_blank"
           class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
            🖨 Cetak PDF
        </a>
    </div>
</div>
@endsection
