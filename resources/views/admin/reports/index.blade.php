@extends('layouts.app')

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

<div class="w-full max-w-screen-2xl mx-auto px-6 py-8 text-white">

    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-pink-400 glow-text animate-fadeSlide">
            <i class="fas fa-chart-line"></i> Laporan Penjualan
        </h1>
        <p class="text-lg text-white/80 animate-fadeSlide" style="animation-delay: .2s">
            Pantau pendapatan, transaksi, dan performa produk dari waktu ke waktu.
        </p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        @foreach([
            ['fa-money-bill-wave', 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'Total Pendapatan'],
            ['fa-check-circle', $successTransactions, 'Transaksi Sukses'],
            ['fa-clock', $pendingTransactions, 'Transaksi Pending'],
            ['fa-receipt', $totalTransactions, 'Total Transaksi'],
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

    <!-- Filter -->
    <div class="mb-12 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 animate-fadeSlide">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
            <div>
                <label class="text-white font-medium block mb-1">Filter Waktu</label>
                <select id="chart-type" name="chart_type"
                        class="bg-pink-600 text-white border border-pink-700 rounded px-4 py-2 shadow hover:scale-105">
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                </select>
            </div>
            <div>
                <label class="text-white font-medium block mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate) }}"
                       class="bg-gray-800 border border-pink-700 text-white rounded px-4 py-2">
            </div>
            <div>
                <label class="text-white font-medium block mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate) }}"
                       class="bg-gray-800 border border-pink-700 text-white rounded px-4 py-2">
            </div>
            <div>
                <button type="submit"
                        class="bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded transition hover:scale-105 hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>

        <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md h-fit shadow animate-fadeSlide">
            <i class="fas fa-file-export"></i> Export ke Excel
        </a>
    </div>

    <!-- Grafik -->
    <div class="bg-dark rounded-xl p-8 mb-12 border border-pink-700 shadow-xl animate-fadeSlide" style="height: 580px;">
        <h2 class="text-2xl font-semibold text-white mb-6">
            <i class="fas fa-chart-area text-pink-400"></i> Grafik Penjualan
        </h2>
        @if(count($dates) > 0 || count($weeklyLabels ?? []) > 0 || count($monthlyLabels ?? []) > 0)
            <canvas id="salesChart" class="w-full h-full"></canvas>
        @else
            <p class="text-center text-white/70"><i class="fas fa-exclamation-triangle"></i> Tidak ada data penjualan untuk grafik.</p>
        @endif
    </div>

    <!-- Produk & Pendapatan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Produk Terlaris -->
        <div class="bg-dark rounded-xl p-6 border border-pink-700 shadow-xl card-glow animate-fadeSlide">
            <h2 class="text-xl font-semibold text-pink-400 mb-4">
                <i class="fas fa-medal text-yellow-400"></i> 5 Produk Terlaris
            </h2>
            <ul class="divide-y divide-white/10 text-white text-base">
                @forelse($topProducts as $name => $qty)
                    <li class="py-2 flex justify-between">
                        <span>{{ $name }}</span>
                        <span class="font-bold">{{ $qty }}x</span>
                    </li>
                @empty
                    <li class="py-2 text-white/60">Belum ada data</li>
                @endforelse
            </ul>
        </div>

        <!-- Distribusi Pendapatan -->
        <div class="bg-dark rounded-xl p-6 border border-pink-700 shadow-xl card-glow animate-fadeSlide">
            <h2 class="text-xl font-semibold text-pink-400 mb-4">
                <i class="fas fa-chart-pie text-indigo-400"></i> Distribusi Pendapatan per Produk
            </h2>
            @if(count($revenueChart) > 0)
                <canvas id="revenueChart" height="200"></canvas>
            @else
                <p class="text-white/60 italic">Belum ada data pendapatan.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = {
        daily: {
            labels: @json($dates),
            data: @json($totals)
        },
        weekly: {
            labels: @json($weeklyLabels ?? []),
            data: @json($weeklyTotals ?? [])
        },
        monthly: {
            labels: @json($monthlyLabels ?? []),
            data: @json($monthlyTotals ?? [])
        }
    };

    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        const ctx = salesCanvas.getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.daily.labels,
                datasets: [{
                    label: 'Total Transaksi',
                    data: chartData.daily.data,
                    backgroundColor: 'rgba(236, 72, 153, 0.2)',
                    borderColor: '#ec4899',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#fff', font: { size: 16 } }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID'),
                            color: '#ccc'
                        },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        ticks: { color: '#ccc' },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });

        document.getElementById('chart-type').addEventListener('change', function () {
            const selected = this.value;
            salesChart.data.labels = chartData[selected].labels;
            salesChart.data.datasets[0].data = chartData[selected].data;
            salesChart.update();
        });
    }

    @if(count($revenueChart) > 0)
    const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($revenueChart)),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json(array_values($revenueChart)),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { labels: { color: '#fff' } }
            },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID'),
                        color: '#ccc'
                    },
                    grid: { color: 'rgba(255,255,255,0.1)' }
                },
                x: {
                    ticks: { color: '#ccc' },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                }
            }
        }
    });
    @endif
</script>
@endpush
