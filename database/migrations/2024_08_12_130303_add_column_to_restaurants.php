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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('has_birthday_services')->default(false);
            $table->boolean('has_discount')->default(false);
            $table->integer('discount');
        });
        Schema::table('dishs', function (Blueprint $table) {
            $table->dropColumn('has_birthday_services');
            $table->dropColumn('has_discount');
            $table->dropColumn('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('has_birthday_services');
            $table->dropColumn('has_discount');
            $table->dropColumn('discount');
        });
    }
};
