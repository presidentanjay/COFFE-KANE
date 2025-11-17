@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-white p-6 md:p-10 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl font-extrabold text-white tracking-tight">📋 Dashboard Kasir</h1>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-800 text-white rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md p-6 text-center">
            <p class="text-sm text-gray-400 mb-1">Transaksi Hari Ini</p>
            <p class="text-3xl font-bold text-indigo-400">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md p-6 text-center">
            <p class="text-sm text-gray-400 mb-1">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold text-green-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md p-6 text-center">
            <p class="text-sm text-gray-400 mb-1">Item Terjual</p>
            <p class="text-3xl font-bold text-yellow-400">{{ $totalItemsSold }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        {{-- Produk --}}
        <div class="lg:col-span-2">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($products as $product)
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow hover:shadow-lg transition flex flex-col justify-between">
                        <div>
                            <div class="text-pink-400 text-2xl mb-2">🍽️</div>
                            <div class="font-semibold text-lg">{{ $product->name }}</div>
                            <div class="text-sm text-gray-400 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <button onclick="addToOrder({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                            class="mt-auto bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg shadow text-sm">
                            ➕ Tambah
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl shadow-lg space-y-6">
            <h2 class="text-xl font-bold">🧾 Pesanan</h2>

            <form action="{{ route('kasir.transactions.store') }}" method="POST">
                @csrf
                <div id="order-list" class="space-y-3"></div>
                <input type="hidden" name="items" id="items-input">

                <div class="border-t border-gray-600 pt-4 text-sm">
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-400">Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-2">
                        <span>Total</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1 text-gray-300">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500">
                        <option value="cash">💵 Tunai</option>
                        <option value="transfer">🏦 Transfer</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold shadow">
                    💾 Simpan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let order = {};

    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function addToOrder(id, name, price) {
        if (order[id]) {
            order[id].qty += 1;
        } else {
            order[id] = { name, price, qty: 1 };
        }
        renderOrderList();
    }

    function decreaseQty(id) {
        if (order[id]) {
            order[id].qty -= 1;
            if (order[id].qty <= 0) {
                delete order[id];
            }
        }
        renderOrderList();
    }

    function removeItem(id) {
        delete order[id];
        renderOrderList();
    }

    function renderOrderList() {
        const list = document.getElementById('order-list');
        list.innerHTML = '';
        let subtotal = 0;
        let itemsArray = [];

        Object.entries(order).forEach(([id, item]) => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            itemsArray.push({ id: parseInt(id), qty: item.qty });

            list.innerHTML += `
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-md">
                    <div class="flex items-center gap-3">
                        <button onclick="decreaseQty(${id})" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">−</button>
                        <span>${item.name} × ${item.qty}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span>${formatRupiah(itemTotal)}</span>
                        <button onclick="removeItem(${id})" class="text-red-400 hover:text-red-600 text-lg font-bold">✕</button>
                    </div>
                </div>
            `;
        });

        const total = subtotal;
        document.getElementById('subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('total').innerText = formatRupiah(total);
        document.getElementById('items-input').value = JSON.stringify(itemsArray);
    }
</script>
@endsection
