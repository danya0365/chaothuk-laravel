<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_reputation_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reviewer_id')->index();
            $table->unsignedBigInteger('reviewee_id')->index();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('booking_type', 20)->nullable(); // work or recruit
            $table->unsignedTinyInteger('quality_rating')->default(5);
            $table->unsignedTinyInteger('timeliness_rating')->default(5);
            $table->unsignedTinyInteger('communication_rating')->default(5);
            $table->unsignedTinyInteger('professionalism_rating')->default(5);
            $table->unsignedTinyInteger('overall_rating')->default(5);
            $table->text('comment')->nullable();
            $table->text('response')->nullable();
            $table->boolean('is_verified_booking')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reputation_reviews');
    }
};
