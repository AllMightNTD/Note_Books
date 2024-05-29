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
        Schema::create('restaurant_info', function (Blueprint $table) {
            //
            $table->text('description_detail');
            $table->unsignedBigInteger('res_category_info_id');
            $table->foreign('res_category_info_id')->references('id')->on('res_category_info')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_info', function (Blueprint $table) {
            //
            Schema::dropIfExists('restaurant_info');
        });
    }
};
