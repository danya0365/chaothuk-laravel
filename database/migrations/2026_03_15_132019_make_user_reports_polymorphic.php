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
        Schema::table('user_reports', function (Blueprint $table) {
            $table->nullableMorphs('reportable'); // Adds reportable_type and reportable_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_reports', function (Blueprint $table) {
            $table->dropMorphs('reportable');
        });
    }
};
