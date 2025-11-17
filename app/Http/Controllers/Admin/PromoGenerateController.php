<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssociationRule;

class PromoGenerateController extends Controller
{
    public function generate()
    {
        // Jalankan perintah artisan apriori:generate
        Artisan::call('apriori:generate');

        // Path file hasil output di folder storage/app/association_rules/
        $file = storage_path('app/association_rules/association_rules_result.csv');

        if (!file_exists($file)) {
            return back()->with('error', 'Gagal menghasilkan file Apriori.');
        }

        // Import CSV ke database
        $handle = fopen($file, 'r');
        fgetcsv($handle); // skip header

        // Hapus data lama
        DB::table('association_rule_product')->delete(); // kalau ada relasi pivot
        DB::table('association_rules')->delete(); // hapus semua rule lama

        while (($data = fgetcsv($handle)) !== false) {
            AssociationRule::create([
                'itemset_antecedent' => $data[0],
                'itemset_consequent' => $data[1],
                'support' => $data[2],
                'confidence' => $data[3],
                'lift' => $data[4],
                'discount_percent' => $data[5] ?? 0,
                'is_active' => true,
            ]);
        }

        fclose($handle);

        return back()->with('success', '✅ Promo berhasil digenerate dan diimport!');
    }
}
