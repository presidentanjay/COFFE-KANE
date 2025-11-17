@extends('layouts.app')

@section('content')
<style>
@keyframes fadeZoom {
    0% { opacity: 0; transform: translateY(10px) scale(0.97); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeZoom {
    animation: fadeZoom 0.4s ease-out forwards;
    opacity: 0;
}
.neon-title {
    text-shadow:
        0 0 5px #ec4899,
        0 0 10px #ec4899,
        0 0 20px #ec4899;
}
</style>

<div class="max-w-3xl mx-auto p-8 min-h-screen bg-[dark] text-white rounded-xl animate-fadeZoom">

    {{-- Judul --}}
    <h2 class="text-3xl font-extrabold neon-title mb-8 flex items-center gap-3">
        <i class="fas fa-file-upload text-pink-400"></i> Upload Data Transaksi
    </h2>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-6 bg-green-700/90 border-l-4 border-green-500 text-green-100 p-4 rounded-lg shadow flex items-center gap-2">
            <i class="fas fa-check-circle text-green-300"></i> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="mb-6 bg-red-700/90 border-l-4 border-red-500 text-red-100 p-4 rounded-lg shadow flex items-center gap-2">
            <i class="fas fa-exclamation-circle text-red-300"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Form Upload CSV --}}
    <form action="{{ route('admin.promos.handleImport') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <input type="file" name="csv_file" accept=".csv" required
                   class="flex-1 bg-gray-900 border border-pink-600 px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-pink-600">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow font-semibold transition flex items-center gap-2">
                <i class="fas fa-cloud-upload-alt"></i> Upload CSV
            </button>
        </div>
    </form>

    {{-- Tombol Generate Rule --}}
    <form action="{{ route('admin.promos.generate') }}" method="POST">
        @csrf
        <button type="submit"
                class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 rounded-lg shadow-lg transition text-lg flex items-center justify-center gap-3">
            <i class="fas fa-sync-alt"></i> Generate Promo Bundling
        </button>
    </form>
</div>
@endsection
