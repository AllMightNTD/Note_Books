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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('dishs', function (Blueprint $table) {
            $table->boolean('has_birthday_services')->default(false);
            $table->boolean('has_discount')->default(false);
            $table->integer('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
        Schema::table('dishs', function (Blueprint $table) {
            $table->dropColumn('has_birthday_services')->default(false);
            $table->dropColumn('has_discount')->default(false);
            $table->dropColumn('discount');
        });
    }
};
