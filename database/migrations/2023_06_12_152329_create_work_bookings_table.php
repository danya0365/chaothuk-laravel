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
        Schema::create('work_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('customer_message')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('booking_status', BookingStatus::getValues())->default(BookingStatus::Default());
            $table->enum('customer_confirm_status', ConfirmStatus::getValues())->default(ConfirmStatus::Default());
            $table->enum('worker_confirm_status', ConfirmStatus::getValues())->default(ConfirmStatus::Default());
            $table->integer('author_id')->unsigned()->index();
            $table->integer('work_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_bookings');
    }
};
