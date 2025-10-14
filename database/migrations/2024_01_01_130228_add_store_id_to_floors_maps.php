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
        Schema::table('floors_maps', function (Blueprint $table) {
            $table->bigInteger('store_id')->unsigned()->index();
            $table->foreign('store_id')->references('id')->on('floors')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::table('floors_maps', function (Blueprint $table) {
//            $table->dropColumn('store_id');
//        });
        Schema::table('floors_maps', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // 刪除外鍵約束
            $table->dropIndex('floors_maps_store_id_index'); // 刪除索引
            $table->dropColumn('store_id'); // 最後再刪除欄位
        });
    }
};
