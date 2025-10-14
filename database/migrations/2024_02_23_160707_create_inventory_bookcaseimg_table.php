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
        Schema::create('inventory_bookcaseimg', function (Blueprint $table) {
            $table->id();
            $table->string("floor");
            $table->string("floormap");
            $table->string("bookcaseord");
            $table->integer("imageord");
            $table->string("url");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_bookcaseimg');
    }
};
