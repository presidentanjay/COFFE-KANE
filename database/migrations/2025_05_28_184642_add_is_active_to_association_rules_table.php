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
    if (!Schema::hasColumn('association_rules', 'is_active')) {
        $table->boolean('is_active')->default(false);
    }
});

}

public function down()
{
    Schema::table('association_rules', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}

};
