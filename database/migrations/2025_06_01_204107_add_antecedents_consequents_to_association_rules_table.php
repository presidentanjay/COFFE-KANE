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
    Schema::table('association_rules', function (Blueprint $table) {
        $table->text('antecedents')->nullable()->after('lift');
        $table->text('consequents')->nullable()->after('antecedents');
    });
}

public function down(): void
{
    Schema::table('association_rules', function (Blueprint $table) {
        $table->dropColumn(['antecedents', 'consequents']);
    });
}

};
