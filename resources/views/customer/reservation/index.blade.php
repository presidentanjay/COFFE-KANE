@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12 text-white">
    <h1 class="text-3xl font-extrabold text-pink-400 mb-4">
        <i class="fas fa-calendar-alt mr-2"></i>Reservation
    </h1>
    <p class="text-gray-300 mb-8">
        Pesan tempat duduk favorit Anda dan nikmati pengalaman ngopi yang lebih eksklusif di Coffee Kane.
    </p>

    {{-- FORM RESERVASI --}}
    <form action="#" method="POST" class="space-y-6 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm text-gray-400 mb-1">Nama Lengkap</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none">
            </div>
            <div>
                <label for="email" class="block text-sm text-gray-400 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none">
            </div>
            <div> 
                <label for="phone" class="block text-sm text-gray-400 mb-1">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" required
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none"
                       placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label for="date" class="block text-sm text-gray-400 mb-1">Tanggal Reservasi</label>
                <input type="date" id="date" name="date" required
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none">
            </div>
            <div>
                <label for="time" class="block text-sm text-gray-400 mb-1">Waktu</label>
                <input type="time" id="time" name="time" required
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm text-gray-400 mb-1">Catatan (Opsional)</label>
            <textarea id="notes" name="notes" rows="3"
                      class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg focus:ring-pink-400 focus:outline-none"
                      placeholder="Contoh: Tempat duduk dekat jendela..."></textarea>
        </div>

        <button type="submit"
                class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
            <i class="fas fa-save mr-2"></i>Simpan Reservasi
        </button>
    </form>
</div>
@endsection
