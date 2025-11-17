@extends('layouts.app')

@section('title', 'Statistik Promo')

@section('content')
<style>
@keyframes fadeSlideUp {
  0% { opacity: 0; transform: translateY(30px) scale(0.98); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeSlide {
  animation: fadeSlideUp 0.6s ease-out forwards;
  opacity: 0;
}
.card-glow:hover {
  box-shadow: 0 8px 20px rgba(236, 72, 153, 0.4), 0 0 10px rgba(236, 72, 153, 0.6);
  transform: translateY(-5px) scale(1.02);
}
.glow-text {
  text-shadow: 0 0 5px #ec4899, 0 0 10px #ec4899;
}
</style>

<div class="w-full px-0 py-8 text-white max-w-7xl mx-auto">
    {{-- Judul Halaman --}}
    <div class="mb-10 animate-fadeSlide" style="animation-delay: 0.1s">
        <h1 class="text-4xl font-extrabold text-pink-400 glow-text">
            <i class="fas fa-chart-line mr-2"></i> Statistik Promo
        </h1>
        <p class="text-lg text-dark mt-2">Pantau performa promo bundling: jumlah muncul, interaksi, dan distribusi diskon.</p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach([
            ['fas fa-chart-bar', $total, 'Total Promo', 'from-pink-600 to-pink-400', 'border-pink-700'],
            ['fas fa-check-circle', $aktif, 'Aktif', 'from-green-600 to-green-400', 'border-green-700'],
            ['fas fa-times-circle', $nonaktif, 'Nonaktif', 'from-red-600 to-red-400', 'border-red-700'],
            ['fas fa-percentage', $avgDiskon . '%', 'Rata-rata Diskon', 'from-blue-600 to-blue-400', 'border-blue-700']
        ] as [$icon, $value, $label, $gradient, $border])
        <div class="bg-gradient-to-br {{ $gradient }} rounded-xl p-6 text-white shadow-xl {{ $border }} border flex items-center gap-4 transition-all duration-500 transform hover:scale-105 card-glow animate-fadeSlide">
            <i class="{{ $icon }} text-3xl"></i>
            <div>
                <div class="text-2xl font-bold">{{ $value }}</div>
                <div class="text-sm text-white/80">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter Tanggal --}}
    <form method="GET" action="{{ route('admin.promos.stats') }}" class="flex flex-wrap md:flex-nowrap gap-4 items-end mb-10 animate-fadeSlide">
        <div>
            <label for="start_date" class="text-sm font-semibold">Start Date</label>
            <input type="date" name="start_date" id="start_date"
                   value="{{ request('start_date') }}"
                   class="w-full mt-1 bg-gray-900 border border-pink-500 rounded px-3 py-2 text-white">
        </div>
        <div>
            <label for="end_date" class="text-sm font-semibold">End Date</label>
            <input type="date" name="end_date" id="end_date"
                   value="{{ request('end_date') }}"
                   class="w-full mt-1 bg-gray-900 border border-pink-500 rounded px-3 py-2 text-white">
        </div>
        <button type="submit"
                class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded-md shadow transition hover:scale-105 hover:shadow-xl">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        @if(request('start_date') && request('end_date'))
            <a href="{{ route('admin.promos.stats') }}" class="text-sm text-pink-400 hover:underline ml-2">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        @endif
    </form>

    {{-- Grafik Interaksi Promo --}}
    @if($promoInteraksi->isNotEmpty())
    <div class="bg-dark rounded-xl p-8 border border-pink-700 shadow-xl mb-12 animate-fadeSlide">
        <h2 class="text-2xl font-semibold text-white mb-6">
            <i class="fas fa-bolt mr-2"></i> Interaksi Promo
        </h2>
        <canvas id="interaksiChart" height="320" class="w-full"></canvas>
    </div>
    @endif

    {{-- Top Produk dan Distribusi Diskon --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-dark rounded-xl p-8 border border-pink-700 shadow-xl animate-fadeSlide">
            <h2 class="text-2xl font-semibold text-pink-400 mb-6">
                <i class="fas fa-fire mr-2"></i> Top 5 Produk Muncul
            </h2>
            <ul class="divide-y divide-white/10 text-white text-base">
                @foreach($topProduk as $produk => $count)
                    <li class="py-2 flex justify-between">
                        <span>{{ $produk }}</span>
                        <span class="font-bold">{{ $count }}x</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-dark rounded-xl p-8 border border-pink-700 shadow-xl animate-fadeSlide">
            <h2 class="text-2xl font-semibold text-pink-400 mb-6">
                <i class="fas fa-bullseye mr-2"></i> Distribusi Diskon
            </h2>
            @if($diskonChart->isNotEmpty())
                <canvas id="diskonChart" height="250"></canvas>
            @else
                <p class="text-white/60 italic">Belum ada data diskon.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($diskonChart->isNotEmpty())
<script>
const ctxDiskon = document.getElementById('diskonChart').getContext('2d');
new Chart(ctxDiskon, {
    type: 'bar',
    data: {
        labels: @json($diskonChart->keys()),
        datasets: [{
            label: 'Jumlah Promo per Diskon (%)',
            data: @json($diskonChart->values()),
            backgroundColor: 'rgba(236, 72, 153, 0.8)',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#fff' } }
        },
        scales: {
            x: { ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { beginAtZero: true, ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.1)' } }
        }
    }
});
</script>
@endif

@if($promoInteraksi->isNotEmpty())
<script>
const ctxInteraksi = document.getElementById('interaksiChart').getContext('2d');
new Chart(ctxInteraksi, {
    type: 'line',
    data: {
        labels: @json($promoInteraksi->pluck('judul')),
        datasets: [
            {
                label: 'Dilihat',
                data: @json($promoInteraksi->pluck('views')),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: '#3b82f6',
                pointRadius: 4,
            },
            {
                label: 'Dipesan',
                data: @json($promoInteraksi->pluck('orders')),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: '#22c55e',
                pointRadius: 4,
            },
            {
                label: 'Disukai',
                data: @json($promoInteraksi->pluck('likes')),
                borderColor: '#eab308',
                backgroundColor: 'rgba(234, 179, 8, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: '#eab308',
                pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#fff' }, position: 'top' }
        },
        scales: {
            x: { ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { beginAtZero: true, ticks: { color: '#eee' }, grid: { color: 'rgba(255,255,255,0.1)' } }
        }
    }
});
</script>
@endif
@endpush
