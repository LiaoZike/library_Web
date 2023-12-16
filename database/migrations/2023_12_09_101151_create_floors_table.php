<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //樓層資料表
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('floors', function (Blueprint $table) {
            $table->id(); //自動增加ID
            $table->integer("ord")->default(0); //順序
            $table->string("name")->default("未命名樓層");; //樓層名稱
            $table->integer("desheight")->default(600);; //設計畫面長度
            $table->integer("deswidth")->default(1500);; //設計畫布寬度
            $table->string("note")->nullable(); //備註
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
