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
        Schema::create('user_mission_status_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('user_mission_status', MissionStatus::values());
            $table->unsignedBigInteger('user_mission_id');
            $table->foreign('user_mission_id')->references('id')->on('user_missions')->cascadeOnDelete();
            $table->unsignedBigInteger('action_user_id');
            $table->foreign('action_user_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::dropIfExists('user_mission_status_logs');
        Schema::enableForeignKeyConstraints();
    }
};
