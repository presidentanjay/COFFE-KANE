@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-gray-200 p-8 max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight">🧾 Daftar Transaksi</h1>
        <a href="{{ route('kasir.transactions.create') }}"
           class="inline-flex items-center bg-indigo-600 text-white px-5 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
            + Transaksi Manual
        </a>
    </div>

    {{-- Filter Pencarian --}}
    <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label for="id" class="block text-sm font-medium">ID Transaksi</label>
            <input type="text" name="id" id="id" value="{{ request('id') }}"
                   class="w-full mt-1 bg-gray-800 border border-gray-700 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium">Status</label>
            <select name="status" id="status"
                    class="w-full mt-1 bg-gray-800 border border-gray-700 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Semua --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <div>
            <label for="start_date" class="block text-sm font-medium">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                   class="w-full mt-1 bg-gray-800 border border-gray-700 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                   class="w-full mt-1 bg-gray-800 border border-gray-700 text-gray-200 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                🔍 Cari
            </button>
        </div>
    </form>

    {{-- Tabel Transaksi --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow overflow-hidden">
        @if ($transactions->isEmpty())
            <div class="p-6 text-center text-gray-400">
                Belum ada transaksi yang ditemukan.
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-700 text-sm">
                <thead class="bg-gray-700 text-gray-200 font-semibold uppercase">
                    <tr>
                        <th class="p-4 text-left">ID Transaksi</th>
                        <th class="p-4 text-left">Total Harga</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($transactions as $trx)
                    <tr class="hover:bg-gray-700 transition">
                        <td class="p-4 font-medium text-gray-100">#{{ $trx->id }}</td>
                        <td class="p-4 text-gray-300">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @php
                                $badge = match($trx->status) {
                                    'pending' => 'bg-yellow-300 text-yellow-900',
                                    'confirmed' => 'bg-green-300 text-green-900',
                                    'canceled' => 'bg-red-300 text-red-900',
                                    default => 'bg-gray-300 text-gray-900'
                                };
                            @endphp
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-400">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="p-4 space-x-2">
                            <a href="{{ route('kasir.transactions.show', $trx->id) }}"
                               class="text-indigo-400 hover:text-indigo-200 font-medium">
                                🔍 Detail
                            </a>
                            <a href="{{ route('kasir.transactions.receipt.pdf', $trx->id) }}"
                               target="_blank"
                               class="text-blue-400 hover:text-blue-200 font-medium">
                                🖨 Cetak
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-6 border-t border-gray-700">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
