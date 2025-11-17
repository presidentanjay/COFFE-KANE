@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">{{ $product->name }}</h1>
        <p class="text-gray-700 mb-6 leading-relaxed">{{ $product->description ?? 'Deskripsi produk tidak tersedia.' }}</p>

        <div class="flex items-center justify-between mb-8">
            <span class="text-3xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @if($product->stock > 0)
                <span class="inline-block px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-full">Stok Tersedia</span>
            @else
                <span class="inline-block px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-full">Stok Habis</span>
            @endif
        </div>

        <a href="{{ route('transactions.create') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg shadow hover:bg-indigo-700 transition mb-10">Pesan Sekarang</a>

        <hr class="mb-10">

        <section>
            <h2 class="text-2xl font-bold mb-6">Ulasan Pelanggan</h2>

            @auth
            <form action="{{ route('reviews.store') }}" method="POST" class="mb-10">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <label class="block mb-2 font-semibold text-gray-800">Rating</label>
                <select name="rating" required class="border border-gray-300 rounded px-3 py-2 mb-4 w-24">
                    @for ($i=1; $i<=5; $i++)
                        <option value="{{ $i }}">{{ $i }} bintang</option>
                    @endfor
                </select>

                <label class="block mb-2 font-semibold text-gray-800">Komentar</label>
                <textarea name="comment" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 resize-none" placeholder="Tulis ulasan kamu di sini..."></textarea>

                <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">Kirim Ulasan</button>
            </form>
            @else
            <p class="mb-10">Silakan <a href="{{ route('login') }}" class="text-indigo-600 underline">login</a> untuk mengirim ulasan.</p>
            @endauth

            @if ($product->reviews->count() > 0)
            <ul class="space-y-6">
                @foreach ($product->reviews as $review)
                <li class="border-b border-gray-200 pb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonim' }}</span>
                        <span class="text-yellow-400 font-bold">{{ $review->rating }} ★</span>
                    </div>
                    <p class="text-gray-700 mb-1">{{ $review->comment ?? '-' }}</p>
                    <small class="text-gray-500">{{ $review->created_at->format('d M Y') }}</small>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @endif
        </section>
    </div>
</div>
@endsection
