<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BadgeType;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('badge_type', BadgeType::values());
            $table->enum('badge_level', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'badge_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
