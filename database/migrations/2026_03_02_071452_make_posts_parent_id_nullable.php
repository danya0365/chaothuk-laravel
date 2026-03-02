<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop the existing FK and column, then re-add as nullable
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->index()->after('author_id');
            $table->foreign('parent_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->index()->after('author_id');
            $table->foreign('parent_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }
};
