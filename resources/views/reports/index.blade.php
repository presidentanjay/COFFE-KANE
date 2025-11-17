@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Penjualan</h1>

    <form method="GET" action="{{ route('admin.report') }}">
        <label>Start Date:</label>
        <input type="date" name="start_date" value="{{ $startDate }}">
        <label>End Date:</label>
        <input type="date" name="end_date" value="{{ $endDate }}">
        <button type="submit">Filter</button>
    </form>

    <!-- Canvas untuk grafik -->
    <canvas id="salesChart" width="400" height="150" style="margin-top: 20px;"></canvas>

    <!-- Tabel data penjualan -->
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 15px;">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Total Quantity Terjual</th>
                <th>Total Penjualan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                <tr>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->total_quantity }}</td>
                    <td>Rp {{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Tidak ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dates),
            datasets: [{
                label: 'Penjualan per Hari',
                data: @json($totals),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
