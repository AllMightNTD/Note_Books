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
        Schema::table('reservations', function (Blueprint $table) {
            //
            $table->dropColumn('booking_time');
            $table->dropColumn('amount_of_people');
            $table->tinyInteger('count_adult');
            $table->tinyInteger('count_child');
            $table->date('date_order')->nullable();
            $table->time('time_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('count_adult');
            $table->dropColumn('count_child');
            $table->dropColumn('date_order')->nullable();
            $table->dropColumn('time_order')->nullable();
        });
    }
};
