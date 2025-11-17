<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociationRule;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    public function index()
    {
        $rules = AssociationRule::where('is_active', true)
            ->orderBy('lift', 'desc')
            ->get()
            ->map(function ($rule) {
                $productNames = collect([
                        ...preg_split('/,|\n/', $rule->itemset_antecedent ?? ''),
                        ...preg_split('/,|\n/', $rule->itemset_consequent ?? '')
                    ])
                    ->map(fn($item) => strtolower(trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $item))))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                // 🔧 Fix: hanya ambil 1 produk untuk setiap nama
                $products = Product::all()
                    ->filter(fn($product) => in_array(strtolower(trim($product->name)), $productNames))
                    ->unique('name');

                if ($products->isEmpty()) {
                    Log::warning("⚠️ INDEX - Tidak ada produk ditemukan untuk rule ID #{$rule->id}", $productNames);
                }

                $hargaNormal = $products->sum('price');
                $rule->discount_percent = $rule->discount_percent ?? 0;
                $rule->harga_normal = $hargaNormal;
                $rule->promo_price = round($hargaNormal * (1 - $rule->discount_percent / 100));

                $rule->products = $products->map(function ($product) use ($rule) {
                    $product->promo_price = round($product->price * (1 - $rule->discount_percent / 100));
                    $product->discount_percent = $rule->discount_percent;
                    return $product;
                });

                return $rule;
            });

        return view('customer.promos.index', compact('rules'));
    }

    public function getPromoProducts($id)
    {
        $rule = AssociationRule::findOrFail($id);

        $productNames = collect([
                ...preg_split('/,|\n/', $rule->itemset_antecedent ?? ''),
                ...preg_split('/,|\n/', $rule->itemset_consequent ?? '')
            ])
            ->map(fn($item) => strtolower(trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $item))))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $products = Product::all()
            ->filter(fn($product) => in_array(strtolower(trim($product->name)), $productNames))
            ->unique('name'); // 🔧 Fix di sini juga

        if ($products->isEmpty()) {
            Log::warning("❌ GET - Tidak ada produk ditemukan untuk promo ID #{$id}", $productNames);
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $discountPercent = $rule->discount_percent ?? 0;

        $response = $products->map(function ($product) use ($discountPercent) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount_percent' => $discountPercent,
                'promo_price' => round($product->price * (1 - $discountPercent / 100)),
            ];
        })->values();

        return response()->json($response);
    }

    public function orderBundle(Request $request)
    {
        $ruleId = $request->input('rule_id');
        $rule = AssociationRule::findOrFail($ruleId);

        $productNames = collect([
                ...preg_split('/,|\n/', $rule->itemset_antecedent ?? ''),
                ...preg_split('/,|\n/', $rule->itemset_consequent ?? '')
            ])
            ->map(fn($item) => strtolower(trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $item))))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $products = Product::all()
            ->filter(fn($product) => in_array(strtolower(trim($product->name)), $productNames))
            ->unique('name'); // 🔧 Fix di sini juga

        if ($products->isEmpty()) {
            Log::warning("❌ orderBundle - Produk tidak ditemukan untuk rule ID #{$rule->id}", $productNames);
            return redirect()->back()->with('error', 'Produk bundling tidak ditemukan.');
        }

        foreach ($products as $product) {
            $cart = session()->get('cart', []);

            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += 1;
            } else {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1
                ];
            }

            session()->put('cart', $cart);
        }

        $hargaNormal = $products->sum('price');
        $diskonPersen = $rule->discount_percent ?? 0;
        $hargaPromo = round($hargaNormal * (1 - $diskonPersen / 100));
        $hemat = $hargaNormal - $hargaPromo;

        session()->put('discount_amount', $hemat);

        return redirect()->route('customer.dashboard')->with('success', 'Promo bundling berhasil dimasukkan ke keranjang!');
    }
}
