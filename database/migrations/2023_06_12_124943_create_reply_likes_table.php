<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reply_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reply_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['reply_id', 'author_id']);

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reply_id')->references('id')->on('replies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_likes');
    }
};
