@extends('layouts.app')

@section('content')
<div class="bg-dark min-h-screen py-10 px-4 sm:px-6 lg:px-8 text-white">
    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-extrabold text-pink-400 mb-8 text-center animate-fadeZoom">
            <i class="fas fa-file-invoice-dollar mr-2"></i>Riwayat Transaksi
        </h1>

        @if($transactions->isEmpty())
            <div class="bg-gray-800 p-6 rounded-lg shadow text-center text-gray-400">
                Belum ada transaksi.
            </div>
        @else
        <div class="bg-gray-800 p-6 rounded-2xl shadow border border-gray-700 animate-fadeZoom">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-700 text-gray-300 uppercase text-xs font-semibold">
                    <tr>
                        <th class="p-3 text-left">ID Transaksi</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                    <tr class="border-t border-gray-700 hover:bg-gray-700/30 transition duration-200">
                        <td class="p-3 font-medium text-white">#{{ $trx->id }}</td>
                        <td class="p-3 text-gray-300">{{ $trx->created_at->format('d M Y H:i') }}</td>
                        <td class="p-3 text-green-400 font-semibold">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="p-3">
                            @php
                                $badge = match($trx->status) {
                                    'pending' => 'bg-yellow-200 text-yellow-900',
                                    'confirmed' => 'bg-green-200 text-green-900',
                                    'canceled' => 'bg-red-200 text-red-900',
                                    default => 'bg-gray-300 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                        <td class="p-3 space-x-3 text-sm">
                            <a href="{{ route('customer.transactions.show', $trx->id) }}"
                               class="inline-flex items-center gap-1 text-indigo-400 hover:text-indigo-300 transition">
                                <i class="fas fa-search"></i> Detail
                            </a>
                            <a href="{{ route('customer.transactions.receipt.pdf', $trx->id) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-blue-400 hover:text-blue-300 transition">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div class="mt-6">
                <div class="bg-gray-900 p-2 rounded text-center">
                    {{ $transactions->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
