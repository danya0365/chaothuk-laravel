<?php

namespace App\Services;

use App\Models\MessengerChannel;
use App\Models\MessengerParticipant;
use App\Models\RecruitBooking;
use App\Models\WorkBooking;
use Illuminate\Support\Str;

/**
 * Opens the chat room a booking belongs to.
 *
 * A booking and its room are created together, so every lookup is idempotent:
 * asking twice for the same booking returns the same room rather than a second
 * one. The unique index on messenger_channels.work_booking_id is what makes that
 * safe under concurrent requests; this service just avoids hitting it.
 */
class MessengerChannelService
{
    public function forWorkBooking(WorkBooking $booking): MessengerChannel
    {
        $existing = MessengerChannel::query()
            ->where('work_booking_id', $booking->id)
            ->first();

        if ($existing) {
            return $this->withParticipants($existing);
        }

        $channel = MessengerChannel::create([
            'slug' => 'work-booking-'.$booking->id.'-'.Str::random(6),
            'title' => $this->workTitle($booking),
            'is_direct' => true,
            'is_public' => false,
            'total_participants' => 2,
            'work_booking_id' => $booking->id,
        ]);

        // The customer asked for the work; the listing owner is the worker. The
        // is_customer flag has to be set explicitly — both rows default to true,
        // which would leave getAdmin() with nobody to return.
        $this->addParticipant($channel, $booking->author_id, true);
        $this->addParticipant($channel, $booking->work->author_id, false);

        return $this->withParticipants($channel);
    }

    public function forRecruitBooking(RecruitBooking $booking): MessengerChannel
    {
        $existing = MessengerChannel::query()
            ->where('recruit_booking_id', $booking->id)
            ->first();

        if ($existing) {
            return $this->withParticipants($existing);
        }

        $channel = MessengerChannel::create([
            'slug' => 'recruit-booking-'.$booking->id.'-'.Str::random(6),
            'title' => $this->recruitTitle($booking),
            'is_direct' => true,
            'is_public' => false,
            'total_participants' => 2,
            'recruit_booking_id' => $booking->id,
        ]);

        // Here the sides are reversed: the applicant is the worker, the listing
        // owner is the employer.
        $this->addParticipant($channel, $booking->author_id, false);
        $this->addParticipant($channel, $booking->recruit->author_id, true);

        return $this->withParticipants($channel);
    }

    /**
     * Find the room for a booking, creating it if this is the first request.
     *
     * Used by the messenger endpoints, which are hit after the booking exists.
     */
    public function ensureForWorkBooking(int $workBookingId): ?MessengerChannel
    {
        $booking = WorkBooking::with('work')->find($workBookingId);

        return $booking ? $this->forWorkBooking($booking) : null;
    }

    public function ensureForRecruitBooking(int $recruitBookingId): ?MessengerChannel
    {
        $booking = RecruitBooking::with('recruit')->find($recruitBookingId);

        return $booking ? $this->forRecruitBooking($booking) : null;
    }

    private function addParticipant(MessengerChannel $channel, int $userId, bool $isCustomer): void
    {
        MessengerParticipant::firstOrCreate(
            [
                'channel_id' => $channel->id,
                'user_id' => $userId,
            ],
            ['is_customer' => $isCustomer],
        );
    }

    private function withParticipants(MessengerChannel $channel): MessengerChannel
    {
        return $channel->load(['participants.author']);
    }

    private function workTitle(WorkBooking $booking): string
    {
        return 'แชทเรื่อง '.($booking->work->title ?? 'ประกาศที่ถูกลบ');
    }

    private function recruitTitle(RecruitBooking $booking): string
    {
        return 'แชทเรื่อง '.($booking->recruit->title ?? 'ประกาศที่ถูกลบ');
    }
}
