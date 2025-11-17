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
    text-shadow:
        0 0 5px #ec4899,
        0 0 10px #ec4899,
        0 0 20px #ec4899;
}
</style>

{{-- Tombol Kembali --}}
<a href="{{ route('admin.products.index') }}"
   class="inline-block mb-6 text-sm text-pink-400 hover:underline animate-fadeZoom"
   style="animation-delay: 0.1s">
   <i class="fas fa-arrow-left"></i> Kembali ke Kelola Produk
</a>

{{-- Judul --}}
<h1 class="text-3xl font-extrabold text-white neon-title mb-6 animate-fadeZoom"
    style="animation-delay: 0.2s">
    <i class="fas fa-plus"></i> Tambah Produk
</h1>

{{-- Form Center --}}
<div class="flex justify-center animate-fadeZoom" style="animation-delay: 0.3s">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-[#db2777] p-6 rounded-2xl shadow-xl space-y-5 w-full max-w-xl border border-pink-700">
        @csrf

        {{-- Nama Produk --}}
        <div>
            <label class="block text-sm text-white font-medium mb-1">Nama Produk</label>
            <input type="text" name="name"
                class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white"
                required>
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm text-white font-medium mb-1">Kategori</label>
            <select name="category_id"
                class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Harga --}}
        <div>
            <label class="block text-sm text-white font-medium mb-1">Harga (Rp)</label>
            <input type="number" name="price"
                class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white"
                required>
        </div>

        {{-- Stok --}}
        <div>
            <label class="block text-sm text-white font-medium mb-1">Stok</label>
            <input type="number" name="stock"
                class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-white"
                required>
        </div>

        {{-- Gambar Produk --}}
        <div>
            <label class="block text-sm text-white font-medium mb-1">Gambar Produk</label>
            <input type="file" name="image" accept="image/*"
                class="w-full bg-[#ec4899] text-white border border-white/20 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-white">
            
            {{-- Preview Gambar (opsional) --}}
            <div id="imagePreview" class="mt-3 hidden">
                <img id="previewImg" class="rounded-lg border border-white/20 max-h-48 mx-auto" alt="Preview Gambar">
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-2 text-right">
            <button type="submit"
                class="bg-[#ec4899] hover:bg-pink-600 text-white font-semibold px-6 py-2 rounded-lg transition-all duration-200">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>

{{-- Script Preview Gambar --}}
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
