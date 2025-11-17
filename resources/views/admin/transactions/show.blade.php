@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<style>
@keyframes fadeSlideUp {
    0% { opacity: 0; transform: translateY(30px) scale(0.98); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeSlide { animation: fadeSlideUp 0.5s ease-out forwards; opacity: 0; }

.card-glow {
    background-color: #111827;
    border: 1px solid #2d2d3a;
    box-shadow: 0 4px 16px rgba(236, 72, 153, 0.3), 0 0 8px rgba(236, 72, 153, 0.4);
    transition: all 0.3s ease;
}
</style>

<div class="min-h-screen bg-gray-950 text-white px-6 py-10 max-w-5xl mx-auto animate-fadeSlide">

    {{-- Header --}}
    <div class="mb-10">
       <h1 class="text-4xl font-extrabold text-pink-500 drop-shadow-[0_0_25px_rgba(236,72,153,0.8)] animate-glow">
    🧾 Detail Transaksi #{{ $transaction->id }}
</h1>


    </div>

    {{-- Ringkasan --}}
    <div class="card-glow rounded-2xl p-6 mb-10">
        <p><strong>Status:</strong>
            <span class="px-3 py-1 rounded-full bg-pink-100 text-pink-800 font-semibold">
                {{ ucfirst($transaction->status) }}
            </span>
        </p>
        <p class="mt-2"><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        <p><strong>Tanggal Pesanan:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('l, d F Y · H:i') }}</p>
    </div>

    {{-- Rincian Produk --}}
    <h2 class="text-2xl font-bold text-pink-400 mb-4">📦 Rincian Produk</h2>
    <div class="overflow-x-auto card-glow rounded-2xl mb-10">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-pink-600 text-white">
                <tr>
                    <th class="p-4 font-semibold">Produk</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold">Jumlah</th>
                    <th class="p-4 font-semibold">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaction->details as $item)
                <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                    <td class="p-4 flex items-center gap-3">
                        <img src="{{ $item->product->image_url ?? '/img/default-product.png' }}" alt="{{ $item->product->name }}"
                             class="w-12 h-12 object-cover rounded-xl border border-gray-700">
                        <span>{{ $item->product->name }}</span>
                    </td>
                    <td class="p-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $item->quantity }}</td>
                    <td class="p-4">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-400 italic">Tidak ada item di transaksi ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Ubah Status --}}
    <div class="card-glow rounded-2xl p-6">
        <form action="{{ route('admin.transactions.update', $transaction) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block text-pink-300 font-semibold mb-1">🔄 Ubah Status</label>
                <select name="status" id="status"
                        class="w-full bg-gray-800 border border-pink-500 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-pink-500">
                    <option value="pending" @if($transaction->status == 'pending') selected @endif>Pending</option>
                    <option value="confirmed" @if($transaction->status == 'confirmed') selected @endif>Confirmed</option>
                    <option value="shipped" @if($transaction->status == 'shipped') selected @endif>Shipped</option>
                    <option value="completed" @if($transaction->status == 'completed') selected @endif>Completed</option>
                    <option value="cancelled" @if($transaction->status == 'cancelled') selected @endif>Cancelled</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-6 py-3 rounded-lg shadow transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
