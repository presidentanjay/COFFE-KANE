@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8 max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Detail Transaksi #{{ $transaction->id }}</h1>

    <div class="bg-white rounded shadow p-6 mb-8">
        <p><strong>Status:</strong> <span class="text-indigo-600">{{ ucfirst($transaction->status) }}</span></p>
        <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        <p><strong>Tanggal Pesanan:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Rincian Produk</h2>

    <table class="w-full bg-white rounded shadow overflow-hidden mb-8">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-4 text-left font-semibold text-gray-700">Produk</th>
                <th class="p-4 text-left font-semibold text-gray-700">Harga</th>
                <th class="p-4 text-left font-semibold text-gray-700">Jumlah</th>
                <th class="p-4 text-left font-semibold text-gray-700">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
            <tr class="border-b border-gray-200">
                <td class="p-4">{{ $item->product->name }}</td>
                <td class="p-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="p-4">{{ $item->quantity }}</td>
                <td class="p-4">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('customer.dashboard') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">Kembali ke Dashboard</a>
</div>
@endsection
