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
        Schema::create('table_regulations', function (Blueprint $table) {
            $table->id();
            // Đặt cọc
            $table->text('deposit')->nullable();
            // Ưu đãi
            $table->text('endow')->nullable();
            // Thời gian nhận khách
            $table->text('reception_time') -> nullable();
            // Thời gian đặt chỗ trước
            $table->text('booking_time')->nullable();
            // Phí mang đồ vào
            $table->text('bill')->nullable();
            // Phí phục vụ
            $table->text('service_charge')->nullable();
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_regulations');
    }
};
