@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-gray-900 text-white rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold mb-6">📊 Laporan Penjualan Harian</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 bg-gray-800 rounded shadow">
            <p class="text-gray-400 text-sm">Total Transaksi</p>
            <p class="text-2xl font-semibold">{{ $totalTransactions }}</p>
        </div>
        <div class="p-4 bg-gray-800 rounded shadow">
            <p class="text-gray-400 text-sm">Total Pendapatan</p>
            <p class="text-2xl font-semibold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="p-4 bg-gray-800 rounded shadow">
            <p class="text-gray-400 text-sm">Item Terjual</p>
            <p class="text-2xl font-semibold">{{ $totalItemsSold }}</p>
        </div>
    </div>
</div>
@endsection
