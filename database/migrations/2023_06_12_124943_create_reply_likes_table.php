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
        Schema::create('reply_likes', function (Blueprint $table) {
            $table->id();
            $table->integer('reply_id')->unsigned()->index();
            $table->integer('author_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['reply_id', 'author_id']);
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