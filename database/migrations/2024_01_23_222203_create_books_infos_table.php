<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void{ //書本資訊
        Schema::create('books_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('local');
            $table->string('local_suff')->nullable()->default("");
            $table->string('number');
            $table->string('url');
            $table->boolean('lend')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('books_infos');
    }
};
