<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_item_id')->nullable()->after('id');

            $table->foreign('transaction_item_id')
                ->references('id')
                ->on('transaction_items')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['transaction_item_id']);
            $table->dropColumn('transaction_item_id');
        });
    }
};
