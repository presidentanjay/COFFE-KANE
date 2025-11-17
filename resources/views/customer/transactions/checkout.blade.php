@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 text-white bg-gray-900 min-h-screen">
    <h2 class="text-3xl font-bold mb-6">
        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
    </h2>

    @if(empty($cart))
        <p class="text-gray-400">Belum ada item di keranjang.</p>
    @else
        <table class="w-full table-auto text-left mb-6">
            <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="p-3">Produk</th>
                    <th class="p-3">Harga Asli</th>
                    <th class="p-3">Harga Setelah Diskon</th>
                    <th class="p-3">Qty</th>
                    <th class="p-3">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @php $total = 0; @endphp
                @foreach($cart as $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="p-3">{{ $item['name'] }}</td>
                        <td class="p-3">Rp {{ number_format($item['original_price'], 0, ',', '.') }}</td>
                        <td class="p-3 text-green-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="p-3">{{ $item['quantity'] }}</td>
                        <td class="p-3 font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-xl font-bold mb-6">
            Total: Rp {{ number_format($total, 0, ',', '.') }}
        </div>

        <div class="text-right">
            <form action="{{ route('transactions.order') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 px-6 py-2 text-white rounded font-semibold transition">
                    <i class="fas fa-credit-card mr-2"></i>Checkout Sekarang
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
