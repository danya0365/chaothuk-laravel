<?php

use App\Enums\IssueStatus;
use App\Enums\IssueType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('issue_points', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('desc');
            $table->decimal('points', 12, 2);
            $table->enum('type', IssueType::values());
            $table->jsonb('cron_info')->nullable();
            $table->string('cron_task')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->enum('status', IssueStatus::values())->default(IssueStatus::SUBMIT->value);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::dropIfExists('issue_points');
        Schema::enableForeignKeyConstraints();
    }
};