@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 shadow rounded-lg mt-10">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Transaksi</h1>

    <div class="text-sm text-gray-700 space-y-2 mb-6">
        <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
        <p><strong>Kasir:</strong> {{ $transaction->user->name }}</p>
        <p><strong>Status:</strong>
            <span class="px-2 py-1 rounded-full 
                @if($transaction->status === 'pending') bg-yellow-100 text-yellow-800 
                @elseif($transaction->status === 'confirmed') bg-green-100 text-green-800 
                @else bg-gray-100 text-gray-800 @endif text-sm">
                {{ ucfirst($transaction->status) }}
            </span>
        </p>
    </div>

    <table class="w-full table-auto text-sm mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left p-2">Produk</th>
                <th class="text-center p-2">Jumlah</th>
                <th class="text-right p-2">Harga</th>
                <th class="text-right p-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product->name }}</td>
                    <td class="p-2 text-center">{{ $item->quantity }}</td>
                    <td class="p-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="p-2 text-right">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right text-lg font-semibold text-gray-800">
        Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('kasir.transactions.index') }}"
           class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
            ⬅ Kembali ke Daftar Transaksi
        </a>
    </div>
</div>
@endsection
