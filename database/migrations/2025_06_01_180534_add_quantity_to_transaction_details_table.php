<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Mengecek apakah kolom qty sudah ada, jika belum, baru ditambahkan
        if (!Schema::hasColumn('transaction_details', 'qty')) {
            Schema::table('transaction_details', function (Blueprint $table) {
                $table->integer('qty')->after('product_id');
            });
        }
    }

    public function down()
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};
