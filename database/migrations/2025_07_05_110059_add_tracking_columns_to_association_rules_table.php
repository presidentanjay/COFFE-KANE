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
    Schema::table('association_rules', function (Blueprint $table) {
        $table->unsignedInteger('views')->default(0);
        $table->unsignedInteger('orders')->default(0);
        $table->unsignedInteger('likes')->default(0);
    });
}

public function down()
{
    Schema::table('association_rules', function (Blueprint $table) {
        $table->dropColumn(['views', 'orders', 'likes']);
    });
}

};
