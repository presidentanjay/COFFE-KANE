<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssociationRule;
use Illuminate\Http\Request;

class PromoStatController extends Controller
{
    public function index()
    {
        $total = AssociationRule::count();
        $aktif = AssociationRule::where('is_active', true)->count();
        $nonaktif = AssociationRule::where('is_active', false)->count();
        $avgDiskon = round(AssociationRule::avg('discount_percent'), 2);

        $allProducts = AssociationRule::select('itemset_antecedent', 'itemset_consequent')->get();
        $produkList = [];

        foreach ($allProducts as $rule) {
            $produkList = array_merge($produkList, explode(', ', $rule->itemset_antecedent));
            $produkList = array_merge($produkList, explode(', ', $rule->itemset_consequent));
        }

        $produkCount = array_count_values($produkList);
        arsort($produkCount);
        $topProduk = array_slice($produkCount, 0, 5, true);

        $diskonChart = AssociationRule::select('discount_percent')->get()->groupBy('discount_percent')->map->count();

        return view('admin.promos.stats', compact(
            'total', 'aktif', 'nonaktif', 'avgDiskon', 'topProduk', 'diskonChart'
        ));
    }
}
