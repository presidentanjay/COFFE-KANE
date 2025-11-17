<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\AssociationRule;
use App\Models\TransactionDetail;

class CustomerController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();

        $promoProducts = Product::whereHas('promos', function ($q) {
            $q->where('promo_product.is_active', true);
        })->get();

        $allProducts = Product::where('stock', '>', 0)->get();

        // Ambil rule promo dan olah nama produk dari kolom itemset_antecedent dan itemset_consequent
        $promoRules = AssociationRule::where('is_active', true)->get()->map(function ($rule) {
            $antecedents = explode(',', $rule->itemset_antecedent ?? '');
            $consequents = explode(',', $rule->itemset_consequent ?? '');
            $rule->product_names = array_filter(array_map('trim', array_merge($antecedents, $consequents)));
            return $rule;
        });

        $transactions = Transaction::where('user_id', $user->id)->latest()->get();
        $cart = session()->get('cart', []);

        return view('customer.dashboard', compact(
            'promoProducts', 'allProducts', 'promoRules', 'transactions', 'cart'
        ));
    }

    public function history(): \Illuminate\View\View
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        return view('customer.transactions.history', compact('transactions'));
    }

    public function orderBundle(Request $request)
    {
        $request->validate([
            'rule_id' => 'required|exists:association_rules,id',
        ]);

        $rule = AssociationRule::findOrFail($request->rule_id);
        $discountPercent = $rule->discount_percent ?? 0;

        // Ambil produk berdasarkan nama dari itemset_antecedent dan itemset_consequent
        $productNames = array_filter(array_map('trim', array_merge(
            explode(',', $rule->itemset_antecedent ?? ''),
            explode(',', $rule->itemset_consequent ?? '')
        )));

        $products = Product::whereIn('name', $productNames)->get();
        $cart = session()->get('cart', []);

        $totalNormal = 0;
        $totalPromo = 0;

        foreach ($products as $product) {
            $productId = $product->id;
            $price = $product->price;

            $totalNormal += $price;
            $totalPromo += round($price * (1 - $discountPercent / 100));

            $cart[$productId] = [
                'name' => $product->name,
                'price' => $price,
                'quantity' => 1,
                'is_bundle' => true,
                'discount' => $discountPercent,
            ];
        }

        session()->put('cart', $cart);
        session()->put('discount_amount', $totalNormal - $totalPromo);

        return redirect()->route('customer.dashboard')->with('success', 'Promo bundling ditambahkan ke keranjang!');
    }

    public function order(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.transactions.checkout')->with('error', 'Keranjang kosong!');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = session()->get('discount_amount', 0);
        $total = max($subtotal - $discount, 0);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'discount_amount' => $discount,
            'status' => 'pending',
            'payment_method' => $request->payment_method ?? 'manual',
        ]);

        foreach ($cart as $productId => $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ]);

            $product = Product::find($productId);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        session()->forget('cart');
        session()->forget('discount_amount');
        session()->put('last_transaction_id', $transaction->id);

        return redirect()->route('customer.transactions.checkout')->with('success', 'Pesanan berhasil dibuat!');
    }
}
