@extends('layouts.app')

@section('content')
<div class="bg-dark min-h-screen py-10 px-4 text-white">
    <div class="max-w-5xl mx-auto space-y-6">

        <h1 class="text-3xl font-extrabold text-white mb-4 text-center">
            <i class="fas fa-receipt text-pink-400 mr-2"></i>Detail Transaksi & Review
        </h1>

        <div class="overflow-x-auto rounded-xl shadow border border-gray-700">
            <table class="min-w-full table-auto text-sm bg-gray-800">
                <thead class="bg-gray-700 text-gray-300 text-xs uppercase font-semibold">
                    <tr>
                        <th class="p-3 text-left"><i class="fas fa-box-open mr-1"></i>Produk</th>
                        <th class="p-3 text-left"><i class="fas fa-tag mr-1"></i>Harga</th>
                        <th class="p-3 text-left"><i class="fas fa-sort-numeric-up mr-1"></i>Jumlah</th>
                        <th class="p-3 text-left"><i class="fas fa-money-bill-wave mr-1"></i>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr class="border-t border-gray-700">
                        <td class="p-3 text-white font-medium">{{ $item->product->name }}</td>
                        <td class="p-3 text-green-400">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-3 text-white">{{ $item->quantity }}</td>
                        <td class="p-3 text-pink-400 font-semibold">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr class="bg-gray-900 border-t border-gray-800">
                        <td colspan="4" class="p-5">
                            @if(!$item->review)
                            <form action="{{ route('customer.review.store') }}" method="POST"
                                class="bg-gray-800 border border-gray-700 p-5 rounded-xl space-y-4 shadow-md">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                                <div>
                                    <label class="block text-sm text-yellow-300 mb-1 font-semibold">
                                        <i class="fas fa-star mr-1"></i>Rating:
                                    </label>
                                    <select name="rating"
                                            class="w-full bg-gray-900 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-pink-500"
                                            required>
                                        <option value="">Pilih Bintang</option>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }} </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm text-pink-300 mb-1 font-semibold">
                                        <i class="fas fa-comment-dots mr-1"></i>Komentar:
                                    </label>
                                    <textarea name="comment" rows="2"
                                            class="w-full bg-gray-900 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:ring-pink-500"
                                            placeholder="Tulis pendapat Anda..."></textarea>
                                </div>

                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg font-semibold transition">
                                    <i class="fas fa-paper-plane mr-1"></i>Kirim Review
                                </button>
                            </form>
                            @else
                            <div class="bg-gray-800 border border-gray-700 p-4 rounded-xl text-sm shadow">
                                <div class="text-green-400 font-semibold mb-1 flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Anda sudah mereview:
                                    <span class="text-yellow-300 font-bold">{{ $item->review->rating }} <i class="fas fa-star"></i></span>
                                </div>
                                <p class="text-gray-300 italic mt-1">"{{ $item->review->comment }}"</p>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
