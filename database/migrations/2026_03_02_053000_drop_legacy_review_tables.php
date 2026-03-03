<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Drop legacy review/reply tables (replaced by posts system).
     */
    public function up(): void
    {
        Schema::dropIfExists('reply_likes');
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('replies');
        Schema::dropIfExists('reviews');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->index();
            $table->string('message')->nullable()->index();
            $table->tinyInteger('rating')->default(0);
            $table->unsignedBigInteger('author_id')->index();
            $table->unsignedBigInteger('work_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
        });

        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->unsignedBigInteger('review_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
        });

        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('reply_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reply_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reply_id')->references('id')->on('replies')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
