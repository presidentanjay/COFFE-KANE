@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-dark text-white p-8 max-w-xl mx-auto">
    <h1 class="text-4xl font-extrabold text-white mb-8">
        <i class="fas fa-pen mr-2"></i>Edit Role
    </h1>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="bg-[#ec4899] p-8 rounded-2xl shadow-xl space-y-6">
        @csrf
        @method('PUT')

        {{-- Label + Input --}}
        <div>
            <label for="name" class="block text-white font-semibold mb-2">Nama Role</label>
            <input type="text" name="name" id="name" value="{{ $role->name }}"
                   class="w-full bg-pink-200/20 text-white placeholder-white border-none px-5 py-3 rounded-xl focus:ring-2 focus:ring-white focus:outline-none"
                   placeholder="Masukkan nama role" required>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-2">
            <button type="submit"
                    class="w-full bg-[#db2777] hover:bg-pink-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                <i class="fas fa-save"></i> Update Role
            </button>
        </div>
    </form>
</div>
@endsection
