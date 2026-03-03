<?php

use App\Enums\IssueStatus;
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
        Schema::create('issue_point_status_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('issue_point_status', IssueStatus::values());
            $table->unsignedBigInteger('issue_point_id');
            $table->foreign('issue_point_id')->references('id')->on('issue_points')->cascadeOnDelete();
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
        Schema::dropIfExists('issue_point_status_logs');
        Schema::enableForeignKeyConstraints();
    }
};
