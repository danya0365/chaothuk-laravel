<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('bookingable');
            $table->unsignedBigInteger('reporter_id')->index();
            $table->unsignedBigInteger('respondent_id')->index();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->jsonb('evidence')->nullable();
            $table->enum('status', ['open', 'investigating', 'resolved', 'closed'])->default('open');
            $table->text('resolution')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('respondent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
