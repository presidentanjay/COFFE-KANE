@extends('layouts.app')

@section('content')
<style>
/* Fade + Slide */
@keyframes fadeSlideUp {
  0% { opacity: 0; transform: translateY(30px) scale(0.98); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeSlide {
  animation: fadeSlideUp 0.6s ease-out forwards;
  opacity: 0;
}

/* Glow on hover */
.card-glow:hover {
  box-shadow: 0 8px 20px rgba(236, 72, 153, 0.4), 0 0 10px rgba(236, 72, 153, 0.6);
  transform: translateY(-5px) scale(1.02);
}

/* Glow text */
.glow-text {
  text-shadow: 0 0 5px #ec4899, 0 0 10px #ec4899;
}
</style>

<div class="mb-12">
    <h1 class="text-4xl font-extrabold text-pink-400 glow-text animate-fadeSlide" style="animation-delay: 0.1s">
        <i class="fas fa-chart-line mr-2"></i>Dashboard Admin
    </h1>
    <p class="text-lg text-dark mt-2 animate-fadeSlide" style="animation-delay: 0.3s">
        Pantau penjualan, stok produk, dan transaksi terbaru.
    </p>
</div>

{{-- STATISTIK --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
    @foreach([
        ['fa-box', $totalProduk, 'Total Produk'],
        ['fa-file-invoice', $totalTransaksi, 'Total Transaksi'],
        ['fa-money-bill-wave', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 'Pendapatan Total'],
        ['fa-users', $totalPengguna, 'Pengguna Terdaftar']
    ] as [$icon, $value, $label])
    <div class="bg-gradient-to-br from-pink-600 to-pink-400 rounded-xl p-6 text-white shadow-xl border border-pink-700 flex items-center gap-4 transition-all duration-500 transform hover:scale-105 card-glow animate-fadeSlide">
        <i class="fas {{ $icon }} text-3xl"></i>
        <div>
            <div class="text-2xl font-bold">{{ $value }}</div>
            <div class="text-sm text-white/80">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- FILTER --}}
<div class="mb-8 animate-fadeSlide">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
        <div>
            <label class="block mb-1 text-white font-semibold">Filter Waktu</label>
            <select name="range" class="bg-pink-600 border border-pink-700 text-white rounded-lg px-4 py-2 shadow hover:scale-105 transition-all"
                onchange="this.form.submit()">
                <option value="daily" {{ $range == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="weekly" {{ $range == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="monthly" {{ $range == 'monthly' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
        <div>
            <label class="block mb-1 text-white font-semibold">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                class="bg-gray-800 border border-pink-700 text-white rounded px-4 py-2">
        </div>
        <div>
            <label class="block mb-1 text-white font-semibold">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                class="bg-gray-800 border border-pink-700 text-white rounded px-4 py-2">
        </div>
        <div>
            <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded transition hover:scale-105 hover:shadow-xl">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
        </div>
    </form>
</div>

{{-- GRAFIK --}}
<div class="bg-dark rounded-xl p-8 mb-12 border border-pink-700 shadow-xl animate-fadeSlide">
    <h2 class="text-2xl font-semibold text-white mb-6"><i class="fas fa-chart-area mr-2"></i>Grafik Penjualan</h2>
    <canvas id="salesChart" height="140"></canvas>
</div>

{{-- PRODUK TERLARIS & STOK MENIPIS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-dark rounded-xl p-6 border border-pink-700 shadow-xl card-glow animate-fadeSlide">
        <h2 class="text-xl font-semibold text-pink-400 mb-4"><i class="fas fa-star mr-2"></i>5 Produk Terlaris</h2>
        <ul class="divide-y divide-white/10 text-white text-base">
            @forelse($topProducts as $product)
            <li class="py-2 flex justify-between">
                <span>{{ $product->name }}</span>
                <span class="font-bold">{{ $product->total_sold }}x</span>
            </li>
            @empty
            <li class="py-2 text-white/60">Belum ada data</li>
            @endforelse
        </ul>
    </div>

    <div class="bg-dark rounded-xl p-6 border border-pink-700 shadow-xl card-glow animate-fadeSlide">
        <h2 class="text-xl font-semibold text-pink-400 mb-4"><i class="fas fa-exclamation-triangle mr-2"></i>Stok Menipis</h2>
        <ul class="divide-y divide-white/10 text-white text-base">
            @forelse($lowStockProducts as $product)
            <li class="py-2 flex justify-between">
                <span>{{ $product->name }}</span>
                <span class="text-pink-300 font-bold">Sisa {{ $product->stock }}</span>
            </li>
            @empty
            <li class="py-2 text-white/60">Stok masih aman</li>
            @endforelse
        </ul>
    </div>
</div>

{{-- TRANSAKSI TERBARU --}}
<div class="mt-12 bg-dark rounded-xl p-6 border border-pink-700 shadow-xl animate-fadeSlide">
    <h2 class="text-xl font-semibold text-pink-400 mb-4"><i class="fas fa-clock mr-2"></i>Transaksi Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-white text-sm md:text-base">
            <thead>
                <tr class="bg-pink-600 text-white text-left">
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Pembayaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse($latestTransactions as $index => $trx)
                <tr class="hover:bg-white/10">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $trx->customer_name ?? '-' }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">
                        @if($trx->status === 'confirmed')
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Sudah Dibayar
                        </span>
                        @else
                        <span class="bg-yellow-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            <i class="fas fa-exclamation-circle mr-1"></i>Belum Dibayar
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-white/60">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesChart['labels']) !!},
        datasets: [{
            label: 'Total Transaksi',
            data: {!! json_encode($salesChart['data']) !!},
            borderColor: '#ec4899',
            backgroundColor: 'rgba(236, 72, 153, 0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: '#fff',
                    font: { size: 16 }
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#eee' },
                grid: { color: 'rgba(255,255,255,0.05)' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#eee' },
                grid: { color: 'rgba(255,255,255,0.05)' }
            }
        }
    }
});
</script>
@endsection
