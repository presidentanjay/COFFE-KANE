@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="flex flex-col md:flex-row gap-8">

    {{-- Foto & Aksi --}}
    <div class="w-full md:w-1/3 flex flex-col items-center bg-gray-800 text-white rounded-2xl p-6">
        <img src="https://i.pravatar.cc/200?u={{ $user->email }}" class="w-40 h-40 rounded-full mb-4 ring-4 ring-pink-400 object-cover">

        <a href="#" class="text-pink-400 hover:underline mb-4">Change Profile Picture</a>

        <a href="{{ route('profile.edit') }}" class="w-full text-center bg-pink-400 hover:bg-pink-500 text-white font-semibold px-4 py-2 rounded mb-3">
            Edit Profile
        </a>

        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full border border-pink-400 text-pink-400 hover:bg-pink-600 hover:text-white px-4 py-2 rounded">
                Delete Profile
            </button>
        </form>
    </div>

    {{-- Informasi --}}
    <div class="w-full md:w-2/3 space-y-6">

        <div class="bg-gray-800 text-white p-6 rounded-2xl">
            <h2 class="text-xl font-bold mb-4">Employee Personal Details</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div><strong>Full Name:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Phone Number:</strong> {{ $user->phone_number ?? '-' }}</div>
                <div><strong>Date of Birth:</strong> {{ $user->birth_date ?? '-' }}</div>
                <div class="md:col-span-2"><strong>Address:</strong> {{ $user->address ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-gray-800 text-white p-6 rounded-2xl">
            <h2 class="text-xl font-bold mb-4">Employee Job Details</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div><strong>Role:</strong> {{ ucfirst($user->role) }}</div>
                <div><strong>Salary:</strong> {{ $user->salary ?? '-' }}</div>
                <div><strong>Shift Start:</strong> {{ $user->shift_start ?? '-' }}</div>
                <div><strong>Shift End:</strong> {{ $user->shift_end ?? '-' }}</div>
            </div>
        </div>

    </div>
</div>
@endsection
