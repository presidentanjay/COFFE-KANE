@extends('layouts.app')

@section('content')
<div class="bg-gray-900 text-white min-h-screen py-10 px-4 md:px-10">
    <h1 class="text-3xl font-bold mb-8 text-white">🛒 Transaksi Manual Kasir</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Daftar Produk --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl shadow hover:shadow-lg transition flex flex-col justify-between">
                        <div>
                            <div class="text-pink-400 text-2xl mb-2">🍽️</div>
                            <div class="font-semibold text-lg">{{ $product->name }}</div>
                            <div class="text-sm text-gray-400 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <button onclick="addToOrder({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                            class="mt-auto bg-pink-500 hover:bg-pink-600 text-white w-full py-2 rounded-lg shadow">
                            ➕ Tambah
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Pesanan --}}
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl shadow-lg space-y-6">
            <div>
                <h2 class="text-xl font-bold mb-2">🪑 Table 01</h2>
                <p class="text-gray-400">Pelanggan Umum</p>
            </div>

            <div id="order-list" class="space-y-4">
                {{-- Item pesanan akan ditambahkan via JS --}}
            </div>

            <div class="border-t border-gray-600 pt-4 text-sm">
                <div class="flex justify-between mb-1">
                    <span class="text-gray-400">Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span class="text-gray-400">Tax 5%</span>
                    <span id="tax">Rp 0</span>
                </div>
                <div class="flex justify-between font-bold text-lg mt-2">
                    <span>Total</span>
                    <span id="total">Rp 0</span>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-sm text-gray-400 mb-2">Payment Method</h3>
                <div class="w-full bg-white p-4 rounded-lg">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=payment-link&size=150x150" alt="QR Code" class="mx-auto">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let order = {};
    let orderIndex = 1;

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

    function changeQty(id, delta) {
        if (order[id]) {
            order[id].qty += delta;
            if (order[id].qty <= 0) delete order[id];
            renderOrderList();
        }
    }

    function renderOrderList() {
        const list = document.getElementById('order-list');
        list.innerHTML = '';

        let subtotal = 0;

        Object.entries(order).forEach(([id, item], index) => {
            const totalPerItem = item.qty * item.price;
            subtotal += totalPerItem;

            const itemHTML = `
                <div class="bg-gray-700 p-3 rounded-lg flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="bg-pink-500 text-xs text-white w-6 h-6 rounded-full flex items-center justify-center">${index + 1}</span>
                        <span>${item.name} × ${item.qty}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <button onclick="changeQty(${id}, -1)" class="px-2 bg-red-600 rounded">-</button>
                        <button onclick="changeQty(${id}, 1)" class="px-2 bg-green-600 rounded">+</button>
                        <span>${formatRupiah(totalPerItem)}</span>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', itemHTML);
        });

        const tax = subtotal * 0.05;
        const total = subtotal + tax;

        document.getElementById('subtotal').innerText = formatRupiah(subtotal);
        document.getElementById('tax').innerText = formatRupiah(tax);
        document.getElementById('total').innerText = formatRupiah(total);
    }
</script>
@endsection
