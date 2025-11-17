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

<div class="flex justify-center animate-fadeZoom" style="animation-delay: 0.1s">
    <div class="w-full max-w-2xl">

        {{-- Judul --}}
        <h1 class="text-3xl font-extrabold text-white neon-title mb-6 animate-fadeZoom"
            style="animation-delay: 0.2s">
            <i class="fas fa-folder-open"></i> Kelola Kategori
        </h1>

        {{-- Tombol Tambah --}}
        <a href="{{ route('admin.categories.create') }}"
           class="bg-[#ec4899] hover:bg-pink-600 text-white px-5 py-2 rounded-lg font-semibold mb-6 inline-block shadow-md animate-fadeZoom transition"
           style="animation-delay: 0.3s">
           <i class="fas fa-plus"></i> Tambah Kategori
        </a>

        {{-- Daftar Kategori --}}
        <div class="bg-[#db2777] rounded-xl shadow-xl p-6 border border-pink-700 animate-fadeZoom" style="animation-delay: 0.4s">
            <ul class="space-y-4">
                @forelse($categories as $cat)
                    <li class="flex justify-between items-center border-b border-white/20 pb-2">
                        <span class="text-white font-medium text-base">{{ $cat->name }}</span>
                        <div class="flex gap-3 text-sm">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="text-white hover:text-blue-300 font-semibold transition">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="text-white hover:text-red-300 font-semibold transition">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="text-white italic">Belum ada kategori.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
