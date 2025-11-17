@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<style>
@keyframes fadeSlideUp {
    0% { opacity: 0; transform: translateY(30px) scale(0.98); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeSlide { animation: fadeSlideUp 0.5s ease-out forwards; opacity: 0; }

.glow-text {
    text-shadow: 0 0 6px #ec4899, 0 0 12px #ec4899;
    color: #f9a8d4;
}

.card-glow:hover {
    box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4), 0 0 8px rgba(236, 72, 153, 0.6);
    transform: translateY(-4px) scale(1.01);
    transition: all 0.3s ease;
}
</style>

<div class="min-h-screen bg-gray-950 text-white px-6 py-10 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-10 animate-fadeSlide" style="animation-delay: 0.1s">
        <h1 class="text-4xl font-extrabold glow-text animate-fadeSlide">
            <i class="fas fa-file-invoice"></i> Riwayat Transaksi
        </h1>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / ID order"
                class="bg-gray-900 border border-pink-500 text-sm px-4 py-2 rounded-xl focus:ring-pink-500 text-white shadow-sm" />

            <div class="flex items-center gap-1">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="bg-gray-900 border border-pink-500 text-sm px-3 py-2 rounded-xl text-white shadow-sm" />
                <span class="text-gray-400">s/d</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="bg-gray-900 border border-pink-500 text-sm px-3 py-2 rounded-xl text-white shadow-sm" />
            </div>

            <select name="filter" onchange="this.form.submit()"
                class="bg-gray-900 border border-pink-500 text-sm px-4 py-2 rounded-xl text-white shadow-sm">
                <option value="">Semua </option>
                <option value="status:pending" {{ request('filter') === 'status:pending' ? 'selected' : '' }}>🕒 Pending</option>
                <option value="status:confirmed" {{ request('filter') === 'status:confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                <option value="status:shipped" {{ request('filter') === 'status:shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                <option value="status:completed" {{ request('filter') === 'status:completed' ? 'selected' : '' }}>🎉 Completed</option>
                <option value="status:cancelled" {{ request('filter') === 'status:cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                <option value="sort:asc" {{ request('filter') === 'sort:asc' ? 'selected' : '' }}>⬆️ Terlama</option>
                <option value="sort:desc" {{ request('filter') === 'sort:desc' ? 'selected' : '' }}>⬇️ Terbaru</option>
            </select>

            <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white text-sm px-4 py-2 rounded-xl font-semibold shadow transition hover:scale-105">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    {{-- Kartu Transaksi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-fadeSlide" style="animation-delay: 0.2s">
        @forelse ($transactions as $index => $transaction)
        <div class="bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-800 card-glow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <div class="bg-pink-500 text-black font-extrabold text-sm px-3 py-1 rounded-xl shadow">
                        {{ str_pad(($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div>
                        <h2 class="font-semibold text-lg">{{ $transaction->customer_name ?? $transaction->user->name }}</h2>
                        <p class="text-xs text-gray-400">Order #{{ $transaction->id }}</p>
                    </div>
                </div>
                <div>
                    @php
                        $statusClass = match($transaction->status) {
                            'pending' => 'bg-white text-gray-900',
                            'confirmed' => 'bg-green-300 text-green-900',
                            'shipped' => 'bg-yellow-300 text-yellow-900',
                            'completed' => 'bg-blue-300 text-blue-900',
                            'cancelled' => 'bg-red-300 text-red-900',
                            default => 'bg-gray-300 text-gray-900'
                        };
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>

            <p class="text-xs text-gray-400 italic mb-3">
                {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('l, d F Y · H:i') }}
            </p>

            <div class="text-sm mb-3 space-y-1">
                @foreach ($transaction->items as $detail)
                <div class="flex justify-between border-b border-gray-800 pb-1">
                    <span>{{ str_pad($detail->quantity, 2, '0', STR_PAD_LEFT) }} × {{ $detail->product->name }}</span>
                    <span class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center font-semibold text-lg text-white border-t border-gray-800 pt-2 mb-4">
                <span>Total</span>
                <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-2 text-sm font-semibold">
                <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                   class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-xl shadow">
                   <i class="fas fa-pen-to-square"></i> Edit
                </a>
                <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl shadow">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                <a href="#" class="flex-1 text-center bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-xl shadow">
                    <i class="fas fa-credit-card"></i> Bayar
                </a>
            </div>
        </div>
        @empty
        <p class="text-center col-span-3 text-gray-400 italic">Tidak ada transaksi.</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $transactions->appends(request()->all())->links() }}
    </div>
</div>
@endsection
