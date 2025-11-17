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

<div class="max-w-7xl mx-auto p-8 min-h-screen bg-[dark] text-white rounded-xl animate-fadeZoom">

    {{-- Judul --}}
    <h2 class="text-4xl font-extrabold neon-title mb-10 flex items-center gap-3">
        <i class="fas fa-bullseye"></i> Promo Bundling Aktif
    </h2>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-6 bg-green-700/90 border-l-4 border-green-500 text-green-100 p-4 rounded-lg shadow">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="mb-6 bg-red-700/90 border-l-4 border-red-500 text-red-100 p-4 rounded-lg shadow">
            <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Aksi Atas --}}
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex gap-3">
            <a href="{{ route('admin.promos.import') }}"
               class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-2 rounded-lg shadow-lg transition">
               <i class="fas fa-file-import"></i> Import CSV
            </a>

            <a href="{{ route('admin.promos.stats') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2 rounded-lg shadow-lg transition">
               <i class="fas fa-chart-bar"></i> Statistik
            </a>

            @php $jumlahNonaktif = $rules->where('is_active', false)->count(); @endphp
            @if ($jumlahNonaktif > 0)
                <form method="POST" action="{{ route('admin.promos.activateAll') }}">
                    @csrf
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-check-circle"></i> Aktifkan Semua
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.promos.deactivateAll') }}">
                    @csrf
                    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-times-circle"></i> Nonaktifkan Semua
                    </button>
                </form>
            @endif
        </div>

        {{-- Filter Sort --}}
        <form method="GET" action="{{ route('admin.promos.index') }}" class="flex items-center gap-2 ml-auto">
            <label for="sort" class="text-sm text-gray-300">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()"
                    class="bg-gray-900 text-white border border-pink-600 rounded px-3 py-2 text-sm focus:ring-pink-500 focus:outline-none">
                <option value="">-- Default --</option>
                <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Diskon Tertinggi</option>
                <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Diskon Terendah</option>
            </select>
        </form>
    </div>

    {{-- Tabel Promo --}}
    <div class="overflow-x-auto shadow-xl rounded-lg border border-pink-600/30 bg-[dark]">
        <table class="min-w-full text-sm text-white table-auto">
            <thead class="bg-pink-600 text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4 text-left">Antecedents</th>
                    <th class="p-4 text-left">Consequents</th>
                    <th class="p-4">Support</th>
                    <th class="p-4">Confidence</th>
                    <th class="p-4">Lift</th>
                    <th class="p-4">Diskon</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach ($rules as $rule)
                <tr class="hover:bg-pink-600/10 transition duration-200 ease-in-out">
                    <td class="p-4">{{ $rule->itemset_antecedent }}</td>
                    <td class="p-4">{{ $rule->itemset_consequent }}</td>
                    <td class="p-4">{{ number_format($rule->support, 4) }}</td>
                    <td class="p-4">{{ number_format($rule->confidence, 4) }}</td>
                    <td class="p-4">{{ number_format($rule->lift, 2) }}</td>
                    <td class="p-4">
                        <form method="POST" action="{{ route('admin.promos.updateDiscount', $rule->id) }}" class="flex gap-2 items-center">
                            @csrf
                            @method('PUT')
                            <input type="number" name="discount_percent" value="{{ $rule->discount_percent }}"
                                   class="w-20 p-2 text-sm rounded border border-pink-500 bg-gray-900 text-white
                                          focus:ring-2 focus:ring-pink-600 transition"
                                   min="0" max="100">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold transition">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </form>
                    </td>
                    <td class="p-4">
                        @if ($rule->is_active)
                            <span class="bg-green-600 text-white text-xs px-3 py-1 rounded-full font-semibold">Aktif</span>
                        @else
                            <span class="bg-red-600 text-white text-xs px-3 py-1 rounded-full font-semibold">Nonaktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-y-1">
                        @if ($rule->is_active)
                            <form method="POST" action="{{ route('admin.promos.deactivate', $rule->id) }}">
                                @csrf
                                <button class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded transition">
                                    <i class="fas fa-times"></i> Nonaktifkan
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.promos.activate', $rule->id) }}">
                                @csrf
                                <button class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded transition">
                                    <i class="fas fa-check"></i> Aktifkan
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
