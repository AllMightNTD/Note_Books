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
        // Schema::table('restaurants', function (Blueprint $table) {
        //     //\
        //     // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        //     $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
        // });
        Schema::table('dishs', function (Blueprint $table) {
            // $table->dropForeign(['category_id']);
            // $table->dropForeign(['sub_category_id']);
            $table->dropColumn('category_id');
            $table->dropColumn('sub_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            //
        });
    }
};
