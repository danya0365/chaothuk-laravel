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
        Schema::create('user_point_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('points', 12, 2);
            $table->unsignedBigInteger('user_point_id');
            $table->foreign('user_point_id')->references('id')->on('user_points')->cascadeOnDelete();
            $table->unsignedBigInteger('action_user_id');
            $table->foreign('action_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('point_transaction_id')->nullable();
            $table->foreign('point_transaction_id')->references('id')->on('point_transaction_logs')->cascadeOnDelete();
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
        Schema::dropIfExists('user_point_logs');
        Schema::enableForeignKeyConstraints();
    }
};