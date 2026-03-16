<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('display_priority')->index();
        });

        Schema::table('recruits', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('display_priority')->index();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('rating')->index();
        });
    }

    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });

        Schema::table('recruits', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('is_suspended');
        });
    }
};
