<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('association_rules', function (Blueprint $table) {
            $table->id();
            $table->text('itemset_antecedent')->nullable(); // Gunakan text + nullable
            $table->text('itemset_consequent')->nullable(); // ← Perbaikan di sini (tambah nullable)
            $table->float('support');
            $table->float('confidence');
            $table->float('lift');
            $table->unsignedTinyInteger('discount_percent')->default(0); // Tambahkan jika belum ada
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_rules');
    }
};
