@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8 max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Checkout Pesanan</h1>

    <form action="{{ route('transactions.store') }}" method="POST" id="orderForm" class="bg-white rounded-lg shadow-lg p-8">
        @csrf

        <table class="w-full mb-6 border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left font-semibold text-gray-700">Produk</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Harga</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="p-4">{{ $product->name }}</td>
                    <td class="p-4">Rp {{ number_format($product->price,0,',','.') }}</td>
                    <td class="p-4">
                        <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox" />
                        <input type="number" name="quantities[]" min="1" value="1" class="w-20 border border-gray-300 rounded px-2 py-1 ml-3 quantity-input" disabled />
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">Pesan Sekarang</button>
    </form>
</div>

<script>
    document.querySelectorAll('.product-checkbox').forEach(function(checkbox, index) {
        checkbox.addEventListener('change', function() {
            const qtyInput = document.querySelectorAll('.quantity-input')[index];
            qtyInput.disabled = !this.checked;
            if (!this.checked) {
                qtyInput.value = 1;
            }
        });
    });
</script>
@endsection
