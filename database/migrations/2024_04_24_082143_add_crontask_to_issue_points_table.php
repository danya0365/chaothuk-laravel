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
        Schema::table('issue_points', function (Blueprint $table) {
            $table->jsonb('cron_info')->nullable();
            $table->string('cron_task')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_points', function (Blueprint $table) {
            $table->dropColumn('cron_info');
            $table->dropColumn('cron_task');
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
        });
    }
};
