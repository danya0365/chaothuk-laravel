<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tie a direct channel to the booking it came from.
     *
     * Without this there is no way to find the room for a booking: the only
     * channels that existed came from the seeder, and the messenger UI told the
     * user to "start a chat by booking work" with nothing behind that claim.
     *
     * Both columns are nullable because existing channels (including the seeded
     * ones) belong to no booking, and a unique index makes opening the room
     * idempotent — two concurrent booking requests cannot create two rooms.
     */
    public function up(): void
    {
        Schema::table('messenger_channels', function (Blueprint $table) {
            $table->unsignedBigInteger('work_booking_id')->nullable()->after('total_participants');
            $table->unsignedBigInteger('recruit_booking_id')->nullable()->after('work_booking_id');

            $table->foreign('work_booking_id')->references('id')->on('work_bookings')->cascadeOnDelete();
            $table->foreign('recruit_booking_id')->references('id')->on('recruit_bookings')->cascadeOnDelete();
        });

        // A booking maps to exactly one room. The index also serves the lookup
        // that opens the room, which would otherwise scan the whole table.
        Schema::table('messenger_channels', function (Blueprint $table) {
            $table->unique('work_booking_id', 'messenger_channels_work_booking_unique');
            $table->unique('recruit_booking_id', 'messenger_channels_recruit_booking_unique');
        });
    }

    public function down(): void
    {
        Schema::table('messenger_channels', function (Blueprint $table) {
            $table->dropUnique('messenger_channels_work_booking_unique');
            $table->dropUnique('messenger_channels_recruit_booking_unique');
        });

        Schema::table('messenger_channels', function (Blueprint $table) {
            $table->dropForeign(['work_booking_id']);
            $table->dropForeign(['recruit_booking_id']);
            $table->dropColumn(['work_booking_id', 'recruit_booking_id']);
        });
    }
};
