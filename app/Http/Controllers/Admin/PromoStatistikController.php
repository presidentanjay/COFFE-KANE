<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociationRule;

class PromoStatistikController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request (opsional)
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Siapkan query dasar
        $query = AssociationRule::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $rules = $query->get();

        // Statistik Umum
        $total     = $rules->count();
        $aktif     = $rules->where('is_active', true)->count();
        $nonaktif  = $total - $aktif;
        $avgDiskon = round($rules->avg('discount_percent'), 2);

        // Hitung kemunculan produk
        $produkCounter = [];
        foreach ($rules as $rule) {
            $items = array_merge(
                array_map('trim', explode(',', $rule->itemset_antecedent)),
                array_map('trim', explode(',', $rule->itemset_consequent))
            );
            foreach ($items as $item) {
                $produkCounter[$item] = ($produkCounter[$item] ?? 0) + 1;
            }
        }
        arsort($produkCounter);
        $topProduk = array_slice($produkCounter, 0, 5, true);

        // Distribusi Diskon
        $diskonChart = $query->selectRaw('discount_percent, COUNT(*) as total')
            ->groupBy('discount_percent')
            ->orderBy('discount_percent')
            ->pluck('total', 'discount_percent');

        // Interaksi Promo: ambil hanya yang punya interaksi
        $promoInteraksi = $query
            ->where(function ($q) {
                $q->where('view_count', '>', 0)
                  ->orWhere('order_count', '>', 0)
                  ->orWhere('like_count', '>', 0);
            })
            ->orderByDesc('view_count')
            ->take(5)
            ->get()
            ->map(function ($rule) {
                return (object)[
                    'judul'  => implode(' + ', array_merge(
                        array_map('trim', explode(',', $rule->itemset_antecedent)),
                        array_map('trim', explode(',', $rule->itemset_consequent))
                    )),
                    'views'  => $rule->view_count,
                    'orders' => $rule->order_count,
                    'likes'  => $rule->like_count,
                ];
            });

        return view('admin.promos.stats', compact(
            'total',
            'aktif',
            'nonaktif',
            'avgDiskon',
            'topProduk',
            'diskonChart',
            'promoInteraksi'
        ));
    }
}
