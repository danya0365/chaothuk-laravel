<?php

use App\Enums\MissionStatus;
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
        Schema::create('user_missions', function (Blueprint $table) {
            $table->id();
            $table->decimal('points', 12, 2);
            $table->dateTime('expired_at');
            $table->enum('status', MissionStatus::values())->default(MissionStatus::IN_PROGRESS->value);
            $table->longText('note')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('banner_promotion_id')->nullable();
            $table->foreign('banner_promotion_id')->references('id')->on('banner_promotions')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_missions');
        Schema::enableForeignKeyConstraints();
    }
};
