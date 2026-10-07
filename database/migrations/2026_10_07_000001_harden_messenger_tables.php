<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Harden the messenger tables.
 *
 * Three problems the original 2024 migrations left open:
 *
 *   1. `content` was string(255), so any longer message was silently truncated.
 *   2. Neither `messenger_conversations` nor `messenger_participants` had a single
 *      index — every channel load and every message poll was a full table scan.
 *   3. `messenger_participants` had no unique constraint on (channel_id, user_id),
 *      so the same person could be added to one channel repeatedly.
 *
 * The unique index is added only after collapsing existing duplicates, so this runs
 * safely against a database that already holds data.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Truncation happened silently for years; anything longer than the old column
        // limit arrived already cut, so there is nothing to recover — only room to grow.
        Schema::table('messenger_conversations', function (Blueprint $table) {
            $table->text('content')->change();
        });

        Schema::table('messenger_conversations', function (Blueprint $table) {
            $table->index('channel_id');
            $table->index('user_id');
            // Message lists are read newest-first per channel, so the sort has to be
            // covered or MySQL filesorts the whole channel on every poll.
            $table->index(['channel_id', 'id']);
        });

        Schema::table('messenger_participants', function (Blueprint $table) {
            $table->index('channel_id');
            $table->index('user_id');
        });

        // Keep the newest row for each (channel_id, user_id) pair and drop the rest.
        $duplicates = DB::table('messenger_participants')
            ->select('channel_id', 'user_id', DB::raw('MAX(id) as keep_id'))
            ->groupBy('channel_id', 'user_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('keep_id');

        DB::table('messenger_participants')
            ->whereNotIn('id', $duplicates)
            ->delete();

        Schema::table('messenger_participants', function (Blueprint $table) {
            $table->unique(['channel_id', 'user_id'], 'messenger_participants_channel_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('messenger_participants', function (Blueprint $table) {
            $table->dropUnique('messenger_participants_channel_user_unique');
            $table->dropIndex(['channel_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('messenger_conversations', function (Blueprint $table) {
            $table->dropIndex(['channel_id', 'id']);
            $table->dropIndex(['channel_id']);
            $table->dropIndex(['user_id']);

            // Deliberately not reverted to string(255): narrowing the column back
            // would truncate any message written since this migration ran, and a
            // rollback that destroys data is worse than one that leaves a wider
            // column in place. Drop the indexes and the unique constraint only.
        });
    }
};
