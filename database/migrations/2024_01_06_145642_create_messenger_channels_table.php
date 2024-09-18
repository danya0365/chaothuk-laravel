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
        Schema::create('messenger_channels', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->boolean('is_direct')->default(true);
            $table->boolean('is_public')->default(false);
            $table->integer('total_participants')->default(2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messenger_channels');
    }
};
