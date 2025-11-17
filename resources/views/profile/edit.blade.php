@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="flex flex-col md:flex-row gap-8">

    {{-- Sidebar Profile Menu --}}
    <div class="w-full md:w-1/4 bg-gray-800 rounded-xl p-6 text-white space-y-4">
        <h2 class="text-xl font-bold">👤 My Profile</h2>
        <ul class="space-y-3">
            <li>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded bg-pink-400 text-white font-semibold">
                    My Profile
                </a>
            </li>
            <li>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
                    ⚙️ Manage Access
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 text-red-400">
                        ⏻ Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    {{-- Form Profile --}}
    <div class="w-full md:w-3/4 bg-gray-800 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold mb-6">Personal Information</h2>

        {{-- Avatar --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="relative">
                <img src="https://i.pravatar.cc/80?u={{ auth()->user()->email }}" class="w-20 h-20 rounded-full ring-2 ring-pink-400 object-cover" />
                <button class="absolute bottom-0 right-0 bg-pink-500 text-white p-1 rounded-full hover:bg-pink-600 text-xs">
                    ✎
                </button>
            </div>
            <div>
                <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-pink-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-semibold">First Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:ring-pink-500 focus:border-pink-500">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" disabled value="{{ auth()->user()->email }}" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-gray-400 cursor-not-allowed">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Address</label>
                <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:ring-pink-500 focus:border-pink-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 font-semibold">New Password</label>
                    <input type="password" name="password" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white">
                </div>

                <div>
                    <label class="block mb-1 font-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white">
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
