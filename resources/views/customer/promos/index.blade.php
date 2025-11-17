@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-dark rounded-xl shadow text-white">
    <h2 class="text-3xl font-bold mb-6 text-pink-400">
        <i class="fas fa-gift mr-2"></i>Promo Bundling Spesial
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($rules as $rule)
            @php
                $produk = $rule->products->pluck('name')->toArray();
                $hargaNormal = $rule->harga_normal;
                $diskonPersen = $rule->discount_percent ?? 0;
                $hargaPromo = $rule->promo_price;
                $hemat = $hargaNormal - $hargaPromo;
            @endphp

            <script>trackView({{ $rule->id }});</script>

            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-5 rounded-2xl shadow-xl border border-gray-700 hover:shadow-2xl transition">
                <h3 class="text-lg font-bold mb-3 text-white leading-relaxed">
                    <i class="fas fa-box-open mr-2"></i>Bundling Produk
                </h3>
                
                <ul class="mb-4 list-disc list-inside text-sm text-white">
                    @foreach ($produk as $p)
                        <li class="ml-1">{{ $p }}</li>
                    @endforeach
                </ul>

                <div class="space-y-1 text-sm text-white mb-4">
                    <p><span class="font-semibold text-green-400"><i class="fas fa-percentage mr-1"></i>Diskon:</span> {{ $diskonPersen }}%</p>
                    <p><span class="font-semibold text-yellow-300"><i class="fas fa-receipt mr-1"></i>Hemat:</span> Rp {{ number_format($hemat, 0, ',', '.') }}</p>
                    <p><span><i class="fas fa-tag mr-1"></i>Harga Normal:</span> Rp {{ number_format($hargaNormal, 0, ',', '.') }}</p>
                    <p><span><i class="fas fa-tags mr-1"></i>Harga Promo:</span> 
                        <span class="text-pink-400 font-semibold">Rp {{ number_format($hargaPromo, 0, ',', '.') }}</span>
                    </p>
                </div>

                <div class="text-xs text-gray-400 mb-4 space-y-1">
                    <p><i class="fas fa-eye mr-1"></i>Dilihat: <span id="view-{{ $rule->id }}">{{ $rule->view_count }}</span></p>
                    <p><i class="fas fa-cart-plus mr-1"></i>Dipesan: <span id="order-{{ $rule->id }}">{{ $rule->order_count }}</span></p>
                    <p><i class="fas fa-heart mr-1"></i>Disukai: <span id="like-{{ $rule->id }}">{{ $rule->like_count }}</span></p>
                </div>

                <button onclick="trackLike({{ $rule->id }})"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-1 rounded-xl font-semibold text-sm mb-2 transition">
                    <i class="fas fa-heart mr-1"></i>Suka Promo Ini
                </button>

                <button onclick="trackOrder({{ $rule->id }}); pilihPromo({{ $rule->id }})"
                    class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-xl font-semibold transition duration-200">
                    <i class="fas fa-cart-plus mr-1"></i>Tambah ke Keranjang
                </button>
            </div>
        @empty
            <p class="col-span-full text-center text-white">Belum ada promo tersedia saat ini.</p>
        @endforelse
    </div>
</div>

{{-- Script Tracking --}}
<script>
    function trackView(id) {
        fetch(`/tracking/view/${id}`, { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    }

    function trackOrder(id) {
        fetch(`/tracking/order/${id}`, { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    }

    function trackLike(id) {
        fetch(`/tracking/like/${id}`, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            const el = document.getElementById('like-' + id);
            if (el) el.innerText = parseInt(el.innerText) + 1;
        });
    }
</script>

{{-- Script Tambah Promo ke Keranjang --}}
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
                    alert('Semua produk promo berhasil ditambahkan ke keranjang!');
                    window.location.href = '{{ route("customer.cart") }}';
                });
            })
            .catch(error => {
                console.error('Gagal ambil produk promo:', error);
                alert('Gagal mengambil produk promo.');
            });
    }

    function tambahKeKeranjang(product) {
        return fetch('{{ route("customer.cart.tambah.promo") }}', {
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
