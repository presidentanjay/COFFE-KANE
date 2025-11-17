<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function tambahNormal(Request $request)
    {
        return $this->tambahInternal($request, 0); // ❌ Tidak pakai diskon
    }

    public function tambahPromo(Request $request)
    {
        $discount = $request->input('discount_percent', 0); // ✅ Ambil diskon dari promo
        return $this->tambahInternal($request, $discount);
    }

    private function tambahInternal(Request $request, $discountPercent)
    {
        Log::debug('🛒 Request masuk ke tambahInternal()', $request->all());

        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        Log::debug("📦 product_id: $productId, quantity: $quantity, diskon: $discountPercent");

        $product = Product::find($productId);
        if (!$product) {
            Log::error("❌ Produk ID $productId tidak ditemukan");
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $discountedPrice = round($product->price * (1 - ($discountPercent / 100)));
        $totalPrice = $discountedPrice * $quantity;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['total_price'] = $cart[$productId]['quantity'] * $discountedPrice;
            Log::debug("📝 Produk diperbarui di keranjang");
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $discountedPrice,
                'original_price' => $product->price,
                'discount_percent' => $discountPercent,
                'quantity' => $quantity,
                'total_price' => $totalPrice
            ];
            Log::debug("✅ Produk ditambahkan ke keranjang");
        }

        session()->put('cart', $cart);
        Log::debug("🧠 Keranjang sekarang:", $cart);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function show()
    {
        $cart = session()->get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        $user = Auth::user();
        $totalPrice = array_sum(array_column($cart, 'total_price'));

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'total' => $totalPrice,
            'discount_amount' => 0,
            'status' => 'pending',
            'customer_name' => $user->name,
        ]);

        foreach ($cart as $productId => $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productId,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('customer.transactions.history')
                         ->with('success', 'Checkout berhasil! Transaksi Anda telah disimpan.');
    }

    public function hapus($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
