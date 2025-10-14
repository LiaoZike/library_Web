<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('bookcasenos', function (Blueprint $table) {
            $table->id();
            $table->string("ord"); #書櫃的某一格
            $table->string("startnum")->nullable()->default(""); #書櫃起始碼
            $table->string("endnum")->nullable()->default(""); #書櫃結束碼
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('bookcasenos');
    }
};
