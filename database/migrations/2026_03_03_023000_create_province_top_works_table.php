<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('province_top_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('province_id')->index();
            $table->unsignedBigInteger('work_id')->index();
            $table->unsignedBigInteger('author_id')->index();

            // Scoring period
            $table->string('period', 7)->index(); // e.g. '2026-03'
            $table->integer('rank')->default(1);   // 1 = top

            // Score breakdown (transparent scoring)
            $table->integer('booking_count')->default(0);      // bookings this month
            $table->integer('confirmed_count')->default(0);    // confirmed bookings
            $table->integer('review_count')->default(0);       // reviews this month
            $table->float('avg_rating')->default(0);           // avg rating this month
            $table->integer('like_count')->default(0);         // total likes
            $table->float('total_score')->default(0);          // computed score

            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnDelete();
            $table->foreign('work_id')->references('id')->on('works')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();

            // One entry per province per period per rank
            $table->unique(['province_id', 'period', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('province_top_works');
    }
};
