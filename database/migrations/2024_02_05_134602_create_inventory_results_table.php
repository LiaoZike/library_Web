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
        Schema::create('inventory_results', function (Blueprint $table) {
            $table->id();
            //$table->string('inventory_time'); //哪一次的盤點
            $table->string('floor'); //第幾樓層 1F
            $table->string('floormap'); //哪一個書櫃 lib01
            $table->string('bookcaseord');  //書櫃第幾格 1
            $table->integer('ishere');  //是否存在 0:不在 1:在 2:錯位
            $table->integer('ord');  //拍攝的書本順序
            $table->integer('matchid');  //辨識對應到的ID
            $table->integer('matchord');  //辨識到的順序

            $table->integer('x1')->nullable()->default(0);  //YOLO辨識範圍
            $table->integer('x2')->nullable()->default(0);  //    "
            $table->integer('y1')->nullable()->default(0);  //    "
            $table->integer('y2')->nullable()->default(0);  //    "

            $table->string('url');  //盤點圖片
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_results');
    }
};
