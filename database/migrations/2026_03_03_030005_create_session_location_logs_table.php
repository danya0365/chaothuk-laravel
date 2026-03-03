<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_location_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('accuracy')->nullable();           // GPS accuracy in meters
            $table->float('speed')->nullable();              // m/s
            $table->float('heading')->nullable();            // degrees
            $table->dateTime('recorded_at');                  // device timestamp
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('work_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_location_logs');
    }
};
