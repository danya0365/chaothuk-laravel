<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BookingStatus;
use App\Enums\ConfirmStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recruit_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('customer_message')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('booking_status', BookingStatus::getValues())->default(BookingStatus::WaitingToConfirm());
            $table->enum('customer_confirm_status', ConfirmStatus::getValues())->default(ConfirmStatus::WaitingToConfirm());
            $table->enum('worker_confirm_status', ConfirmStatus::getValues())->default(ConfirmStatus::WaitingToConfirm());
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('recruit_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recruit_id')->references('id')->on('recruits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_bookings');
    }
};
