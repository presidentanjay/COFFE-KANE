<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Total Quantity Terjual</th>
            <th>Total Penjualan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
        <tr>
            <td>{{ $sale->name }}</td>
            <td>{{ $sale->total_quantity }}</td>
            <td>{{ $sale->total_sales }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
