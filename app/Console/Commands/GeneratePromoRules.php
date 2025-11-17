<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GeneratePromoRules extends Command
{
    protected $signature = 'apriori:generate';
    protected $description = 'Menjalankan Python script Apriori untuk menghasilkan promo bundling';

    public function handle()
    {
        $this->info("Menjalankan Apriori...");
        
        // Jalankan script Python
        $process = new Process(['python3', base_path('generate_rules.py')]);
        
        // =================================================================
        // PERBAIKAN: Atur direktori kerja ke root project Laravel.
        // Ini memastikan skrip Python dapat menemukan path relatif 
        // seperti 'storage/app' dengan benar.
        $process->setWorkingDirectory(base_path());
        // =================================================================

        $process->run();

        // Jika gagal
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info("Selesai! Output disimpan di association_rules_result.csv");
    }
}
