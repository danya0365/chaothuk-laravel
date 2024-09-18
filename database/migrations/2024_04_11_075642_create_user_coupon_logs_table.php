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
        Schema::create('user_coupon_logs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('coupons');
            $table->unsignedBigInteger('user_coupon_id');
            $table->foreign('user_coupon_id')->references('id')->on('user_coupons')->cascadeOnDelete();
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
        Schema::dropIfExists('user_coupon_logs');
        Schema::enableForeignKeyConstraints();
    }
};
