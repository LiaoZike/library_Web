<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{ //書櫃擺放
        Schema::create('floors_maps', function (Blueprint $table) {
            $table->id(); //固定id
            $table->string("bookcaseName"); //書櫃編碼
            $table->string("bookcaseNote")->nullable(); //書櫃備註
            $table->integer("top")->default(0); //Top
            $table->integer("left")->default(0); //Left
            $table->integer("height")->default(0); //高度
            $table->integer("width")->default(0); //寬度
            $table->integer("rotate")->default(0); //寬度
            $table->integer("severalrows")->default(5); //書櫃有幾格5*4(5個row)
            $table->integer("severalcols")->default(4); //書櫃有幾格5*4(4個col)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors_maps');
    }
};
