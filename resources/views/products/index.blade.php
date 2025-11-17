@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8 max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Daftar Produk</h1>

    <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-4 mb-8">
        <input
            type="text"
            name="search"
            placeholder="Cari produk..."
            value="{{ request('search') }}"
            class="flex-grow border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        />

        <select
            name="category_id"
            class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select
            name="stock_status"
            class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
            <option value="">Semua Stok</option>
            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
        </select>

        <button
            type="submit"
            class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700 transition"
        >
            Cari
        </button>
    </form>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Deskripsi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Harga</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Stok</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->description ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ $product->category ? $product->category->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->stock > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Tersedia</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Habis</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-indigo-600 hover:text-indigo-900">
                            <a href="{{ route('products.show', $product) }}" class="font-medium">Detail &raquo;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">Produk tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection
