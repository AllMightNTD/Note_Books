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
            $table->double('price_min',12,2)->change();
            $table->double('price_max',12,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishs', function (Blueprint $table) {
            //
            $table->dropColumn('price_min');
        });
    }
};
