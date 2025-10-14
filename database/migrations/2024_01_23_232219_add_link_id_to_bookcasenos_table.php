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
        Schema::table('bookcasenos', function (Blueprint $table) {
            $table->bigInteger('link_id')->unsigned()->index();
            $table->foreign('link_id')->references('id')->on('floors_maps')->onDelete('cascade');;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('bookcasenos', function (Blueprint $table) {
            // 先刪除外鍵約束
            $table->dropForeign(['link_id']);
            // 再刪除索引
            $table->dropIndex('bookcasenos_link_id_index');
            // 最後再刪除欄位
            $table->dropColumn('link_id');
        });
    }
};

