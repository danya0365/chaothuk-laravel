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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_can_create_recruit')->default(0);
            $table->boolean('is_can_create_work')->default(0);
            $table->boolean('is_can_review_work')->default(0);
            $table->boolean('is_can_reply_review')->default(0);
            $table->boolean('is_can_access_supervisor')->default(0);
            $table->boolean('is_can_access_admin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
