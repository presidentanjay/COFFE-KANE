<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\AssociationRule;

class TransactionController extends Controller
{
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('customer.transactions.create', compact('products'));
    }

    public function order(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100'
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // Hitung total tanpa diskon
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        // Hitung diskon bundling
        $rules = AssociationRule::with('items')->where('is_active', true)->get();
        $discountTotal = 0;

        foreach ($rules as $rule) {
            $productIds = $rule->items->pluck('product_id')->toArray();

            $isAllInCart = collect($productIds)->every(function ($id) use ($cart) {
                return isset($cart[$id]);
            });

            if ($isAllInCart) {
                $ruleTotal = collect($productIds)->sum(function ($id) use ($cart) {
                    return $cart[$id]['price'] * $cart[$id]['quantity'];
                });

                $discount = ($ruleTotal * ($rule->discount_percent ?? 0)) / 100;
                $discountTotal += $discount;
            }
        }

        $finalTotal = $total - $discountTotal;

        // FIXED: tambahkan field 'total' agar tidak error
        $transaction = Transaction::create([
            'user_id'         => Auth::id(),
            'total'           => $total, // harga sebelum diskon
            'total_price'     => $finalTotal, // setelah diskon
            'discount_amount' => $discountTotal,
            'status'          => 'pending',
            'customer_name'   => $request->customer_name
        ]);

        foreach ($cart as $productId => $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $productId,
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
            ]);

            Product::find($productId)->decrement('stock', $item['quantity']);
        }

        session()->forget('cart');

        return redirect()->route('customer.transactions.history')
            ->with('success', 'Pesanan berhasil dibuat!')
            ->with('last_transaction_id', $transaction->id);
    }

    public function show(Transaction $transaction)
    {
        return view('customer.transactions.show', compact('transaction'));
    }

    public function history()
    {
        $transactions = Transaction::with('items.product')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.transactions.history', compact('transactions'));
    }
}
