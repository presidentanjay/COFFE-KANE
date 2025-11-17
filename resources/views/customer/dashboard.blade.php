@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.app')

@push('styles')
<style>
@keyframes fadeZoom {
    0% { opacity: 0; transform: translateY(20px) scale(0.97); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeZoom {
    animation: fadeZoom 0.5s ease-out forwards;
    opacity: 0;
}
</style>
@endpush

@section('content')
<div class="bg-dark min-h-screen text-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6 animate-fadeZoom" style="animation-delay: 0.05s">
            <h1 class="text-3xl font-extrabold text-white">
                <i class="fas fa-mug-hot mr-2"></i>Sistem Pemesanan online
            </h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.cart') }}" class="text-white hover:text-pink-400 text-xl">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="{{ route('customer.transactions.history') }}"
                   class="bg-pink-600 hover:bg-pink-700 px-4 py-2 rounded text-white font-semibold text-sm transition">
                    <i class="fas fa-receipt mr-1"></i> Riwayat
                </a>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <form method="GET" action="{{ route('customer.dashboard') }}"
              class="mb-6 animate-fadeZoom" style="animation-delay: 0.1s">
            <input type="text" name="search" placeholder="Cari produk..."
                   value="{{ request('search') }}"
                   class="w-full md:w-1/3 bg-gray-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500" />
        </form>

        {{-- FILTER KATEGORI --}}
        <div class="mb-6 animate-fadeZoom" style="animation-delay: 0.15s">
            <h2 class="text-xl font-bold text-pink-400 mb-3">
                <i class="fas fa-folder-open mr-2"></i>Kategori
            </h2>
            <div class="flex flex-wrap gap-2">
                @php
                    $categories = ['All', 'Coffee', 'Non Coffee', 'Manual Brew', 'Main Course', 'Snack', 'Rice Bowl'];
                    $currentCategory = request('category', 'All');
                @endphp

                @foreach($categories as $cat)
                    <a href="{{ route('customer.dashboard', ['category' => $cat !== 'All' ? $cat : null]) }}"
                       class="px-4 py-2 rounded-full text-sm font-semibold transition
                       {{ $currentCategory === $cat ? 'bg-pink-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- PRODUK BIASA --}}
        <h2 class="text-2xl font-bold text-pink-400 mb-4 animate-fadeZoom" style="animation-delay: 0.2s">
            <i class="fas fa-utensils mr-2"></i>Daftar Menu
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12 animate-fadeZoom" style="animation-delay: 0.25s">
            @foreach($allProducts->filter(fn($p) => $currentCategory === 'All' || $p->category->name === $currentCategory)
                              ->filter(fn($p) => stripos($p->name, request('search')) !== false) as $product)
            <div class="bg-pink-500 text-white rounded-xl shadow-md p-4 flex flex-col justify-between hover:scale-105 transition-transform duration-200">
                {{-- FIXED: Gambar Produk --}}
                <div class="bg-white rounded-lg h-32 flex items-center justify-center mb-3 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="h-full w-full object-cover">
                    @else
                        <img src="{{ asset('images/placeholder-food.png') }}" 
                             alt="Placeholder" 
                             class="h-20 object-contain opacity-50">
                    @endif
                </div>

                <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                <p class="text-white font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-white mb-2 {{ $product->stock <= 0 ? 'text-red-200' : '' }}">
                    Stok: {{ $product->stock }}
                </p>

                <form action="{{ route('customer.transactions.order') }}" method="POST" class="flex items-center gap-2 mt-auto">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" min="1" max="{{ $product->stock }}" value="1"
                           class="w-16 rounded bg-white text-black text-center font-semibold"
                           {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <button type="submit"
                            class="flex-1 bg-gray-900 hover:bg-gray-800 text-white px-3 py-1 rounded font-semibold transition disabled:opacity-50"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        {{ $product->stock <= 0 ? 'Habis' : 'Tambah' }}
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- PROMO BUNDLING --}}
        @if(!empty($promoRules) && $promoRules->count() > 0)
        <div class="mt-10 animate-fadeZoom" style="animation-delay: 0.3s">
            <h2 class="text-2xl font-bold text-yellow-300 mb-4">
                <i class="fas fa-tags mr-2"></i>Promo Bundling
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($promoRules as $rule)
                    <div class="bg-pink-600 text-white p-5 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:scale-105">
                        <h3 class="text-lg font-bold mb-3 leading-relaxed">
                            <i class="fas fa-box-open mr-2"></i>Bundling Produk
                        </h3>

                        @if(!empty($rule->product_names))
                            <ul class="mb-4 list-disc list-inside text-sm space-y-1">
                                @foreach ($rule->product_names as $name)
                                    <li>{{ $name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm mb-4 italic">Tidak ada produk dalam bundling ini.</p>
                        @endif

                        <p class="text-sm mb-4">
                            <span class="font-semibold">
                                <i class="fas fa-percentage mr-1"></i>Diskon:
                            </span> {{ $rule->discount_percent ?? 0 }}%
                        </p>

                        <button onclick="pilihPromo({{ $rule->id }})"
                                class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2 rounded-xl font-semibold transition duration-200">
                            Tambah ke Keranjang
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- SCRIPT PROMO --}}
<script>
    function pilihPromo(promoId) {
        fetch(`/customer/promos/${promoId}/products`)
            .then(res => res.json())
            .then(products => {
                if (!products || products.length === 0) {
                    alert("Tidak ada produk dalam promo ini.");
                    return;
                }

                const promises = products.map(product => tambahKeKeranjang(product));
                Promise.all(promises).then(() => {
                    alert('Promo ditambahkan ke keranjang!');
                });
            })
            .catch(error => {
                console.error('Gagal ambil produk promo:', error);
                alert('Gagal mengambil produk promo.');
            });
    }

    function tambahKeKeranjang(product) {
        return fetch('/keranjang/tambah', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: product.id,
                quantity: 1,
                discount_percent: product.discount_percent ?? 0
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log(`${product.name} ditambahkan:`, data);
        })
        .catch(error => {
            console.error(`Gagal tambah ${product.name}:`, error);
        });
    }
</script>
@endsection