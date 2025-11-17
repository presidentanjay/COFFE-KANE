<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('association_rule_product', function (Blueprint $table) {
    $table->unsignedBigInteger('rule_id');
    $table->unsignedBigInteger('product_id');

    $table->foreign('rule_id')->references('id')->on('association_rules')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_rule_product');
    }
};
