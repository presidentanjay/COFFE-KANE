@extends('layouts.app')

@section('content')
<style>
@keyframes fadeZoom {
    0% { opacity: 0; transform: translateY(20px) scale(0.97); }
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

<div class="p-6 min-h-screen bg-[dark] text-white">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.users.index') }}"
           class="inline-block text-sm text-pink-400 hover:underline animate-fadeZoom">
            ← Kembali ke Kelola User
        </a>

        {{-- Judul Halaman --}}
        <h1 class="text-3xl font-extrabold text-white neon-title animate-fadeZoom">
            <i class="fas fa-user-plus mr-2"></i>Tambah User
        </h1>

        {{-- Form Box --}}
        <div class="bg-[#ec4899] p-10 rounded-2xl shadow-xl space-y-6 border border-pink-700 animate-fadeZoom">

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-500/20 text-red-100 p-4 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-white text-sm mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full bg-pink-200/20 text-white placeholder-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                        placeholder="Nama lengkap" required>
                </div>

                <div>
                    <label class="block text-white text-sm mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-pink-200/20 text-white placeholder-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                        placeholder="Email aktif" required>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-white text-sm mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full bg-pink-200/20 text-white placeholder-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                            placeholder="Minimal 6 karakter" required>
                    </div>

                    <div>
                        <label class="block text-white text-sm mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full bg-pink-200/20 text-white placeholder-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                            placeholder="Ulangi password" required>
                    </div>
                </div>

                <div>
                    <label class="block text-white text-sm mb-1">Role</label>
                    <select name="role"
                        class="w-full bg-pink-200/20 text-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                        required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                            class="bg-[#db2777] hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-save"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
