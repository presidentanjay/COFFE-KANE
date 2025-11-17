@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold mb-6 text-pink-500">
        <i class="fas fa-shopping-bag mr-2"></i>Pesan Produk
    </h1>

    <form action="{{ route('customer.transactions.order') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block font-medium text-gray-300">Pilih Produk</label>
            <select name="product_id" id="product_id"
                    class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 text-white focus:ring-pink-500 focus:outline-none">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} (Rp {{ number_format($product->price) }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-semibold transition">
            <i class="fas fa-paper-plane mr-2"></i>Pesan Sekarang
        </button>
    </form>
</div>
@endsection
