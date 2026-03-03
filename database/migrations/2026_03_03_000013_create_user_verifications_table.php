<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\VerificationType;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('verification_type', VerificationType::values());
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('proof_data')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->unique(['user_id', 'verification_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
