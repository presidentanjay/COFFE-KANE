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
    Schema::create('sales_data', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->integer('total_sales');
        $table->string('period_type'); // daily, weekly, monthly
        $table->timestamps();
    });
}

};
