@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 text-white bg-dark min-h-screen">
    <h2 class="text-3xl font-extrabold mb-6 flex items-center gap-2">
        <i class="fas fa-shopping-cart text-pink-400"></i> Keranjang Belanja
    </h2>

    @if(empty($cart))
        <p class="text-gray-400 italic">Belum ada item di keranjang.</p>
    @else
        <div class="overflow-x-auto rounded-xl shadow border border-gray-700">
            <table class="min-w-full table-auto text-sm bg-gray-800">
                <thead class="bg-gray-700 text-gray-300 text-xs uppercase font-semibold">
                    <tr>
                        <th class="p-3 text-left"><i class="fas fa-box-open mr-1"></i>Produk</th>
                        <th class="p-3 text-left"><i class="fas fa-tag mr-1"></i>Harga Asli</th>
                        <th class="p-3 text-left"><i class="fas fa-percent mr-1"></i>Harga Diskon</th>
                        <th class="p-3 text-left"><i class="fas fa-sort-numeric-up mr-1"></i>Qty</th>
                        <th class="p-3 text-left"><i class="fas fa-money-bill-wave mr-1"></i>Subtotal</th>
                        <th class="p-3 text-left"><i class="fas fa-cogs mr-1"></i>Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @php $total = 0; @endphp
                    @foreach($cart as $productId => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="p-3 font-medium text-white">{{ $item['name'] }}</td>
                            <td class="p-3 text-gray-300">
                                @if(isset($item['original_price']))
                                    <span class="line-through text-red-400">
                                        Rp {{ number_format($item['original_price'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="italic text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-green-400">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </td>
                            <td class="p-3">{{ $item['quantity'] }}</td>
                            <td class="p-3 font-semibold text-pink-400">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                            <td class="p-3">
                                <a href="{{ route('customer.cart.hapus', $productId) }}"
                                   onclick="return confirm('Hapus produk ini dari keranjang?')"
                                   class="text-red-400 hover:text-red-600 font-semibold text-sm">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right mt-6 text-xl font-bold text-white">
            Total: <span class="text-pink-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <div class="text-right mt-4">
            <form action="{{ route('customer.cart.checkout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-6 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-credit-card mr-1"></i>Checkout Sekarang
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
