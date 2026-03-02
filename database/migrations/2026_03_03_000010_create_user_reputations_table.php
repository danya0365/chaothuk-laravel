<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TrustLevel;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_reputations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->decimal('overall_score', 3, 2)->default(0);
            $table->decimal('quality_score', 3, 2)->default(0);
            $table->decimal('timeliness_score', 3, 2)->default(0);
            $table->decimal('communication_score', 3, 2)->default(0);
            $table->decimal('professionalism_score', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedInteger('total_completed_jobs')->default(0);
            $table->unsignedInteger('total_cancelled_jobs')->default(0);
            $table->decimal('completion_rate', 5, 2)->default(0);
            $table->decimal('response_rate', 5, 2)->default(0);
            $table->unsignedInteger('avg_response_minutes')->default(0);
            $table->unsignedInteger('repeat_customer_count')->default(0);
            $table->enum('trust_level', TrustLevel::values())->default(TrustLevel::NEW_USER->value);
            $table->unsignedInteger('total_points_earned')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reputations');
    }
};
