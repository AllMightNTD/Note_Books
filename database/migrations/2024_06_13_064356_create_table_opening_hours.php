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
        Schema::create('day_in_weeks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('symbol', 20);
            $table->timestamps();
        });

        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('day_in_week_id')->unsigned();
            $table->foreign('day_in_week_id')
                ->references('id')
                ->on('day_in_weeks');
            $table->time('open_time');
            $table->time('close_time');
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('days_in_week');
        Schema::dropIfExists('opening_hours');
    }
};
