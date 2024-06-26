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
        Schema::table('dishs', function (Blueprint $table) {
            //
            $table -> dropColumn("type_of_restaurant");
        });
        Schema::table('restaurants', function (Blueprint $table) {
            //
            $table -> string("type_of_restaurant") -> nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table -> dropColumn("type_of_restaurant");
        });
    }
};
