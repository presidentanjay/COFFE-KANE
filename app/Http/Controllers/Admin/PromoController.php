<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociationRule;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = AssociationRule::query();

        if ($request->sort === 'highest') {
            $query->orderByDesc('discount_percent');
        } elseif ($request->sort === 'lowest') {
            $query->orderBy('discount_percent');
        } else {
            $query->orderByDesc('lift');
        }

        $rules = $query->get();
        return view('admin.promos.index', compact('rules'));
    }

    public function showImportForm()
    {
        return view('admin.promos.import');
    }

    public function handleImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $fileName = 'transactions_apriori_ready_with_bundling.csv';
        $folderPath = storage_path('app/transactions');

        // Buat folder jika belum ada
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Simpan file ke storage/app/transactions
        $request->file('csv_file')->move($folderPath, $fileName);

        return redirect()->route('admin.promos.import')->with('success', '✅ File CSV berhasil diupload ke storage/app/transactions');
    }

    public function import(Request $request)
    {
        $filePath = storage_path('app/transactions/transactions_apriori_ready_with_bundling.csv');

        if (!file_exists($filePath)) {
            return back()->with('error', ' File tidak ditemukan. Upload terlebih dahulu!');
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return back()->with('error', ' Gagal membuka file');
        }

        $header = fgetcsv($handle);

        DB::beginTransaction();
        try {
            AssociationRule::truncate();

            while (($row = fgetcsv($handle)) !== false) {
                $lift = floatval($row[4]);
                $diskon = $lift >= 3.0 ? 20 : ($lift >= 2.5 ? 15 : ($lift >= 2.0 ? 10 : 5));

                AssociationRule::create([
                    'itemset_antecedent' => $row[0],
                    'itemset_consequent' => $row[1],
                    'support' => $row[2],
                    'confidence' => $row[3],
                    'lift' => $lift,
                    'is_active' => false,
                    'discount_percent' => $diskon,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.promos.index')->with('success', '📊 Import rule berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }
    }

    public function generate()
    {
        $inputPath = storage_path('app/transactions/transactions_apriori_ready_with_bundling.csv');

        if (!file_exists($inputPath)) {
            return back()->with('error', '❌ File transaksi tidak ditemukan. Silakan upload terlebih dahulu.');
        }

        $command = "python3 generate_rules.py \"$inputPath\"";
        exec($command, $output, $status);

        if ($status === 0) {
            return back()->with('success', 'Rules berhasil digenerate dan disimpan ke CSV!');
        } else {
            return back()->with('error', 'agal generate rules. Silakan cek script Python!');
        }
    }

    public function activate($id)
    {
        AssociationRule::where('id', $id)->update(['is_active' => true]);
        return back()->with('success', 'Promo berhasil diaktifkan');
    }

    public function deactivate($id)
    {
        AssociationRule::where('id', $id)->update(['is_active' => false]);
        return back()->with('success', 'Promo berhasil dinonaktifkan');
    }

    public function getPromoProducts($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $productNames = [$rule->itemset_antecedent, $rule->itemset_consequent];
        $products = Product::whereIn('name', $productNames)->get();

        return response()->json($products);
    }

    public function updateDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $rule = AssociationRule::findOrFail($id);
        $rule->update([
            'discount_percent' => $request->discount_percent,
        ]);

        return back()->with('success', 'Diskon berhasil diperbarui!');
    }

    public function stats(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : now()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date) : now();

        $promos = AssociationRule::where('is_active', true)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $total = $promos->count();
        $aktif = $promos->where('is_active', true)->count();
        $nonaktif = 0;
        $avgDiskon = round($promos->avg('discount_percent'), 1);

        $topProduk = [];
        foreach ($promos as $promo) {
            $produk = array_merge(
                explode(',', $promo->itemset_antecedent),
                explode(',', $promo->itemset_consequent)
            );
            foreach ($produk as $item) {
                $item = trim($item);
                $topProduk[$item] = ($topProduk[$item] ?? 0) + 1;
            }
        }
        arsort($topProduk);
        $topProduk = array_slice($topProduk, 0, 5);

        $diskonChart = $promos->groupBy('discount_percent')->map->count();

        $promoInteraksi = $promos->map(function ($promo) {
            $produk = array_merge(
                explode(',', $promo->itemset_antecedent),
                explode(',', $promo->itemset_consequent)
            );

            $judul = collect($produk)
                ->map(fn($item) => trim($item))
                ->filter()
                ->implode(' + ');

            return (object)[
                'judul' => $judul ?: '(Tanpa Judul)',
                'views' => $promo->view_count ?? 0,
                'orders' => $promo->order_count ?? 0,
                'likes' => $promo->like_count ?? 0,
            ];
        });

        return view('admin.promos.stats', compact(
            'total', 'aktif', 'nonaktif', 'avgDiskon',
            'topProduk', 'diskonChart', 'promoInteraksi'
        ));
    }

    public function activateAll()
    {
        AssociationRule::where('is_active', false)->update(['is_active' => true]);
        return back()->with('success', ' Semua promo berhasil diaktifkan!');
    }

    public function deactivateAll()
    {
        AssociationRule::where('is_active', true)->update(['is_active' => false]);
        return back()->with('success', ' Semua promo berhasil dinonaktifkan!');
    }
}
