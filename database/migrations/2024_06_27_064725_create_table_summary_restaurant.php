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
        // Tóm tắt chi tiết nhà hàng
        Schema::create('table_summary_restaurant', function (Blueprint $table) {
            $table->id();
            // Độ phù hợp
            $table->text('suitability');
            // Món đặc biệt
            $table->text('special_dish');
            // Không gian
            $table->text('space');
            // Chỗ để xe
            $table->text('parking');
            // Điểm đặc trưng
            $table->text('speciality');
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
        Schema::dropIfExists('table_summary_restaurant');
    }
};
