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
.neon-title {
    font-size: 1.75rem;
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
<a href="{{ route('admin.products.index') }}"
   class="inline-block mb-6 text-sm text-pink-400 hover:underline animate-fadeZoom"
   style="animation-delay: 0.1s">
   <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
</a>

<h1 class="neon-title mb-6 animate-fadeZoom" style="animation-delay: 0.2s">
    <i class="fas fa-pen"></i> Edit Produk
</h1>

{{-- Form Center --}}
<div class="flex justify-center">
    <form action="{{ route('admin.products.update', $product->id) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-[#db2777] p-6 rounded-2xl shadow-xl space-y-6 w-full max-w-xl border border-pink-700 animate-fadeZoom"
          style="animation-delay: 0.3s">
        @csrf
        @method('PUT')

        {{-- Nama Produk --}}
        <div>
            <label class="block text-sm text-white font-medium mb-2">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                   class="w-full rounded-lg bg-[#ec4899] text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white focus:border-white/20" />
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm text-white font-medium mb-2">Kategori</label>
            <select name="category_id"
                    class="w-full rounded-lg bg-[#ec4899] text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white focus:border-white/20">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Harga --}}
        <div>
            <label class="block text-sm text-white font-medium mb-2">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                   class="w-full rounded-lg bg-[#ec4899] text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white focus:border-white/20" />
        </div>

        {{-- Stok --}}
        <div>
            <label class="block text-sm text-white font-medium mb-2">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                   class="w-full rounded-lg bg-[#ec4899] text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white focus:border-white/20" />
        </div>

        {{-- Gambar Produk --}}
        <div>
            <label class="block text-sm text-white font-medium mb-2">Gambar Produk</label>

            {{-- Tampilkan gambar lama --}}
            @if ($product->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="rounded-lg border border-white/20 max-h-48 mx-auto">
                </div>
            @endif

            {{-- Input upload gambar baru --}}
            <input type="file" name="image" accept="image/*"
                   class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-white">

            {{-- Preview gambar baru --}}
            <div id="imagePreview" class="mt-3 hidden">
                <img id="previewImg" class="rounded-lg border border-white/20 max-h-48 mx-auto" alt="Preview Gambar Baru">
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="pt-2 text-right">
            <button type="submit"
                    class="bg-[#ec4899] hover:bg-pink-600 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 shadow-md">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

{{-- Script Preview Gambar Baru --}}
<script>
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const previewImg = document.getElementById('previewImg');
            const previewBox = document.getElementById('imagePreview');
            previewImg.src = event.target.result;
            previewBox.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
