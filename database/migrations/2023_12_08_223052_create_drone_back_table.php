<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::create('drone_back', function (Blueprint $table) {
            $table->id();
            $table->integer("linkid");
            $table->string("book_shelf");
            $table->string("predict_picture1");
            $table->string("predict_picture2");
            $table->integer("state");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('drone_back');
    }
};
