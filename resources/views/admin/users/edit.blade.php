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
            <i class="fas fa-pen mr-2"></i>Edit User
        </h1>

        {{-- Form Box --}}
        <div class="bg-[#ec4899] p-10 rounded-2xl shadow-xl space-y-6 border border-pink-700 animate-fadeZoom">

            {{-- Form --}}
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-white text-sm mb-1">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="w-full bg-pink-200/20 text-white border-none px-4 py-3 rounded-lg placeholder-white focus:ring-2 focus:ring-white focus:outline-none"
                        placeholder="Nama lengkap" required>
                </div>

                <div>
                    <label class="block text-white text-sm mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full bg-pink-200/20 text-white border-none px-4 py-3 rounded-lg placeholder-white focus:ring-2 focus:ring-white focus:outline-none"
                        placeholder="Email aktif" required>
                </div>

                <div>
                    <label class="block text-white text-sm mb-1">Role</label>
                    <select name="role"
                            class="w-full bg-pink-200/20 text-white border-none px-4 py-3 rounded-lg focus:ring-2 focus:ring-white focus:outline-none"
                            required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->roles->first()?->id == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                            class="bg-[#db2777] hover:bg-pink-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 transition">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
