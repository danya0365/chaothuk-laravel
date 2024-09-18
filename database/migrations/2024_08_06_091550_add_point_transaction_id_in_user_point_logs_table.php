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
        Schema::table('user_point_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('point_transaction_id')->nullable();
            $table->foreign('point_transaction_id')->references('id')->on('point_transaction_logs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_point_logs', function (Blueprint $table) {
            $table->dropColumn('point_transaction_id');
        });
    }
};
