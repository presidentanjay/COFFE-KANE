<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'transaction_id' => 'required|exists:transactions,id',
        'product_id' => 'required|exists:products,id',
        'transaction_item_id' => 'required|exists:transaction_items,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    \App\Models\Review::create([
        'user_id' => auth()->id(),
        'transaction_id' => $request->transaction_id,
        'product_id' => $request->product_id,
        'transaction_item_id' => $request->transaction_item_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return redirect()->back()->with('success', 'Review berhasil dikirim!');
}

}
