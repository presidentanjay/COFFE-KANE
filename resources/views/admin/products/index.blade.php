@extends('layouts.app')

@section('content')
<style>
@keyframes fadeZoom {
    0% { opacity: 0; transform: translateY(20px) scale(0.97); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeZoom {
    animation: fadeZoom 0.5s ease-out forwards;
    opacity: 0;
}
.card-hover:hover {
    transform: scale(1.025);
    box-shadow: 0 0 15px rgba(236, 72, 153, 0.5);
}
.neon-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #fff;
    text-shadow:
        0 0 5px #ec4899,
        0 0 10px #ec4899,
        0 0 20px #ec4899,
        0 0 40px #ec4899;
}
</style>

{{-- Tombol Kembali --}}
<a href="{{ route('admin.dashboard') }}"
   class="inline-block mb-6 text-sm text-pink-400 hover:underline animate-fadeZoom"
   style="animation-delay: 0.1s">
   <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
</a>

{{-- Judul dan Tombol --}}
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <h1 class="neon-title animate-fadeZoom" style="animation-delay: 0.2s">
        <i class="fas fa-utensils"></i> Kelola Produk
    </h1>
    <div class="flex gap-3">
        <a href="{{ route('admin.categories.index') }}"
           class="bg-pink-700 hover:bg-pink-800 text-white px-4 py-2 rounded-xl font-semibold shadow-md animate-fadeZoom transition"
           style="animation-delay: 0.3s">
            <i class="fas fa-folder-open"></i> Kelola Kategori
        </a>
        <a href="{{ route('admin.products.create') }}"
           class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-xl font-semibold shadow-md animate-fadeZoom transition"
           style="animation-delay: 0.4s">
            <i class="fas fa-plus-circle"></i> Tambah Produk
        </a>
    </div>
</div>

{{-- Grid Produk --}}
@if ($products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-fadeZoom" style="animation-delay: 0.5s">
        @foreach ($products as $product)
            <div class="bg-[#ec4899] p-5 rounded-xl shadow-lg flex flex-col justify-between card-hover transition duration-300 ease-in-out">
                <div>
                    {{-- Gambar Produk --}}
                    <div class="bg-pink-100 rounded-lg h-32 flex items-center justify-center mb-3 overflow-hidden">
                        @php
                            $imagePath = $product->image ? asset('storage/' . ltrim($product->image, '/')) : asset('img/placeholder-food.png');
                        @endphp
                        <img src="{{ $imagePath }}" 
                             alt="{{ $product->name }}" 
                             class="h-20 object-contain transition duration-300">
                    </div>

                    {{-- Info Produk --}}
                    <h3 class="text-white font-bold text-lg mb-1">{{ $product->name }}</h3>
                    <p class="text-white font-semibold mb-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm {{ $product->stock > 0 ? 'text-white/90' : 'text-red-200' }}">
                        {{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Stok Habis' }}
                    </p>
                </div>

                {{-- Aksi --}}
                <div class="mt-4 flex justify-between text-sm">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="text-white hover:text-blue-400 font-medium transition">
                       Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-white hover:text-red-300 font-medium transition">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-white/70 text-center italic animate-fadeZoom" style="animation-delay: 0.6s">
        Belum ada produk
    </p>
@endif
@endsection
