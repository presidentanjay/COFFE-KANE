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

<div class="min-h-screen bg-[dark] text-white px-8 py-10 max-w-7xl mx-auto animate-fadeZoom">
    
    {{-- Judul --}}
    <h1 class="text-4xl font-extrabold text-white neon-title mb-10">
        <i class="fas fa-shield-alt mr-2"></i>Daftar Role
    </h1>

    {{-- Tombol Tambah --}}
    <a href="{{ route('admin.roles.create') }}"
       class="bg-[#ec4899] hover:bg-pink-600 text-white px-6 py-3 rounded-lg mb-6 inline-block font-semibold shadow transition">
        <i class="fas fa-plus mr-2"></i>Tambah Role
    </a>

    {{-- Tabel --}}
    <div class="overflow-x-auto border border-[#ec4899]/40 shadow-lg rounded-2xl bg-[dark]">
        <table class="min-w-full divide-y divide-[#ec4899]/30 text-base">
            <thead class="bg-[#ec4899] text-white uppercase text-sm tracking-wider">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold">Role</th>
                    <th class="px-8 py-4 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse ($roles as $role)
                <tr class="hover:bg-[#ec4899]/30 transition">
                    <td class="px-8 py-5 text-white font-medium">{{ ucfirst($role->name) }}</td>
                    <td class="px-8 py-5 flex gap-4">
                        <a href="{{ route('admin.roles.edit', $role) }}"
                           class="text-blue-400 hover:text-blue-600 font-semibold transition">
                           <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus role ini?')"
                                    class="text-red-500 hover:text-red-700 font-semibold transition">
                                <i class="fas fa-trash-alt mr-1"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center py-6 text-pink-200 italic">Belum ada data role.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
@endsection
