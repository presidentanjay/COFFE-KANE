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

<div class="p-6 bg-[dark] min-h-screen text-white animate-fadeZoom">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center animate-fadeZoom" style="animation-delay: 0.1s">
            <h1 class="text-3xl font-extrabold text-white neon-title">
                <i class="fas fa-users mr-2"></i>Staff Management
            </h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.create') }}"
                   class="bg-[#ec4899] hover:bg-pink-600 text-white px-5 py-2 rounded-xl font-semibold shadow transition-all duration-200">
                    + Tambah Staff
                </a>
                <select class="bg-[#ec4899] text-white text-sm font-semibold rounded-xl px-3 py-2 focus:ring-2 focus:ring-pink-400 focus:outline-none shadow">
                    <option class="bg-dark text-white">Sort by</option>
                    <option class="bg-dark text-white">Nama</option>
                    <option class="bg-dark text-white">Status</option>
                    <option class="bg-dark text-white">Tanggal</option>
                </select>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto mt-6 rounded-2xl shadow-xl border border-[#ec4899]/40 animate-fadeZoom" style="animation-delay: 0.2s">
            <table class="min-w-full text-sm text-white rounded-xl overflow-hidden">
                <thead class="bg-[#ec4899] text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ec4899]/30">
                    @forelse ($users as $user)
                        <tr class="bg-[#1a1a2e] hover:bg-[#ec4899]/20 transition duration-200">
                            <td class="px-4 py-3 font-mono text-[#ec4899]">#{{ $user->id }}</td>
                            <td class="px-4 py-3 flex items-center gap-3">
                                <img src="{{ $user->profile_photo_url ?? 'https://i.pravatar.cc/40?u=' . $user->id }}"
                                     alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-[#ec4899] shadow-sm">
                                <div>
                                    <div class="font-semibold text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-pink-300 italic">{{ $user->roles->pluck('name')->join(', ') }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-white/90">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-white/90">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="px-4 py-3 space-y-1">
                                <span class="inline-flex items-center gap-1 bg-green-500 text-white text-xs px-3 py-1 rounded-full shadow">
                                    <i class="fas fa-circle text-white text-[8px]"></i> Hadir
                                </span><br>
                                <span class="inline-flex items-center gap-1 bg-yellow-400 text-dark text-xs px-3 py-1 rounded-full shadow">
                                    <i class="fas fa-exclamation-triangle text-yellow-800"></i> Absen
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded-full shadow transition">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-full shadow transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-pink-300 italic">Belum ada data staff.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
