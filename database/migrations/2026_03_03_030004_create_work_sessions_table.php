<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->id();
            $table->morphs('sessionable');                           // work or recruit
            $table->unsignedBigInteger('worker_id')->index();
            $table->unsignedBigInteger('customer_id')->index();

            // Optional — may or may not come from a booking
            $table->nullableMorphs('bookingable');                   // work_booking or recruit_booking

            // Time tracking
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('total_duration_minutes')->default(0);

            // Agreed price
            $table->decimal('price_agreed', 10, 2)->nullable();

            // Status
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->text('cancel_reason')->nullable();

            // Both-side confirmation
            $table->enum('worker_confirm', ['pending', 'confirmed', 'disputed'])->default('pending');
            $table->enum('customer_confirm', ['pending', 'confirmed', 'disputed'])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_sessions');
    }
};
