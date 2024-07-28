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
        Schema::create('dishs_image', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dish_id');
            $table->foreign('dish_id')->references('id')->on('dishs');
            $table->string('image');
            $table->string('cloud_id');
            $table->timestamps();
        });

        Schema::table('dishs', function (Blueprint $table) {
            $table->dropColumn('thumb_nail');
            $table->dropColumn('cloud_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishs_image');
    }
};
