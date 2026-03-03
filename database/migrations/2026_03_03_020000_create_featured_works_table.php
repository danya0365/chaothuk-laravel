<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id')->index();
            $table->unsignedBigInteger('author_id')->index();

            // Featured period
            $table->dateTime('start_at');
            $table->dateTime('end_at');

            // Slot position (for ordering in carousel)
            $table->integer('slot_position')->default(0);

            // Payment tracking
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // 'points', 'transfer', 'credit_card', etc.
            $table->unsignedBigInteger('point_transaction_id')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, refunded

            // Admin control
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();

            // Impression tracking
            $table->integer('impression_count')->default(0);
            $table->integer('click_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('work_id')->references('id')->on('works')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('point_transaction_id')->references('id')->on('point_transaction_logs')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_works');
    }
};
