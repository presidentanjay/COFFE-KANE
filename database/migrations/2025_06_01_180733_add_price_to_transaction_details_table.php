<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Menambahkan kolom 'price' tanpa mengacu pada urutan kolom 'quantity'
            $table->integer('price')->after('product_id');  // Gantilah 'product_id' sesuai dengan kolom yang ada
        });
    }

    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
