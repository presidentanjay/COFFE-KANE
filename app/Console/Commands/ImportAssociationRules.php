<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\AssociationRule;
use Illuminate\Support\Facades\DB;

class ImportAssociationRules extends Command
{
    protected $signature = 'import:association-rules {file=association_rules_result.csv}';
    protected $description = 'Import association rules dari file CSV ke database';

    public function handle()
    {
        $fileArg = $this->argument('file');
        $file = file_exists($fileArg) ? $fileArg : base_path($fileArg);

        if (!file_exists($file)) {
            $this->error("❌ File tidak ditemukan: $file");
            return 1;
        }

        $data = array_map('str_getcsv', file($file));
        $header = array_map(fn($h) => trim(strtolower($h)), array_shift($data)); // pastikan lowercase dan trim

        // Validasi header wajib
        $requiredHeaders = ['antecedents', 'consequents', 'support', 'confidence', 'lift'];
        foreach ($requiredHeaders as $col) {
            if (!in_array($col, $header)) {
                $this->error("❌ Kolom '$col' tidak ditemukan di file CSV.");
                return 1;
            }
        }

        DB::beginTransaction();
        try {
            foreach ($data as $index => $row) {
                if (count($row) !== count($header)) {
                    $this->warn("⚠️ Baris ke-" . ($index + 2) . " dilewati karena jumlah kolom tidak sesuai.");
                    continue;
                }

                $row = array_combine($header, $row);
                if ($row === false) {
                    $this->warn("⚠️ Gagal menggabungkan header dan data di baris ke-" . ($index + 2));
                    continue;
                }

                $antecedentsRaw = trim($row['antecedents'] ?? '');
                $consequentsRaw = trim($row['consequents'] ?? '');

                if (empty($antecedentsRaw) || empty($consequentsRaw)) {
                    $this->warn("⚠️ Baris ke-" . ($index + 2) . " dilewati karena antecedents atau consequents kosong.");
                    continue;
                }

                $antecedents = array_map('trim', explode(',', $antecedentsRaw));
                $consequents = array_map('trim', explode(',', $consequentsRaw));
                $allItems = array_unique(array_merge($antecedents, $consequents));

                $productIds = Product::whereIn('name', $allItems)->pluck('id', 'name')->toArray();
                $missing = array_diff($allItems, array_keys($productIds));
                if (count($missing)) {
                    $this->warn("⚠️ Produk tidak ditemukan (baris ke-" . ($index + 2) . "): " . implode(', ', $missing));
                    continue;
                }

                $discountPercent = isset($row['discount_percent']) && trim($row['discount_percent']) !== ''
                    ? (int) trim($row['discount_percent'])
                    : 0;

                $rule = AssociationRule::create([
                    'itemset_antecedent' => implode(', ', $antecedents),
                    'itemset_consequent' => implode(', ', $consequents),
                    'support' => (float) $row['support'],
                    'confidence' => (float) $row['confidence'],
                    'lift' => (float) $row['lift'],
                    'is_active' => false,
                    'discount_percent' => $discountPercent,
                ]);

                $rule->items()->attach($productIds);

                $this->info("✅ Baris ke-" . ($index + 2) . ": " . implode(' + ', $antecedents) . " => " . implode(' + ', $consequents) . " (Diskon: {$discountPercent}%)");
            }

            DB::commit();
            $this->info('🎉 Semua rule berhasil diimport!');
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Gagal import: ' . $e->getMessage());
            return 1;
        }
    }
}
