<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('inventory_results', function (Blueprint $table) {
            $table->bigInteger('link_id')->unsigned()->index();
            $table->foreign('link_id')->references('id')->on('inventory_times')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('inventory_results', function (Blueprint $table) {
            $table->dropForeign(['link_id']);
            $table->dropColumn('link_id');
        });
    }
};
