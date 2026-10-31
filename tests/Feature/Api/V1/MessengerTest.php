<?php

namespace Tests\Feature\Api\V1;

use App\Enums\BookingStatus;
use App\Enums\ConfirmStatus;
use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Services\MessengerChannelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MessengerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\GeographySeeder::class);
        $this->seed(\Database\Seeders\ProvinceSeeder::class);
        $this->seed(\Database\Seeders\WorkTypeSeeder::class);
    }

    public function test_listing_channels_requires_authentication(): void
    {
        $this->getJson('/api/v1/messenger/channels')->assertUnauthorized();
    }

    public function test_opening_a_booking_channel_creates_a_room_with_both_sides(): void
    {
        $worker = User::factory()->create();
        $customer = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $worker->id]);
        $booking = $this->makeWorkBooking($work, $customer);

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/work-bookings/{$booking->id}/channel");

        $response->assertCreated()
            ->assertJsonPath('data.booking_type', 'work-booking')
            ->assertJsonPath('data.booking_id', $booking->id)
            ->assertJsonPath('data.counterpart.id', $worker->id);

        $this->assertDatabaseCount('messenger_channels', 1);
        $this->assertDatabaseCount('messenger_participants', 2);

        // Both rows default to is_customer = true, so without an explicit flag
        // the channel would have no worker for getAdmin() to find.
        $this->assertDatabaseHas('messenger_participants', [
            'channel_id' => $response->json('data.id'),
            'user_id' => $customer->id,
            'is_customer' => true,
        ]);
        $this->assertDatabaseHas('messenger_participants', [
            'channel_id' => $response->json('data.id'),
            'user_id' => $worker->id,
            'is_customer' => false,
        ]);
    }

    public function test_opening_the_same_booking_twice_returns_the_same_room(): void
    {
        $worker = User::factory()->create();
        $customer = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $worker->id]);
        $booking = $this->makeWorkBooking($work, $customer);

        $first = $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/work-bookings/{$booking->id}/channel")
            ->json('data.id');

        $second = $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/work-bookings/{$booking->id}/channel")
            ->json('data.id');

        $this->assertSame($first, $second);
        $this->assertDatabaseCount('messenger_channels', 1);
    }

    public function test_the_worker_can_open_the_room_too(): void
    {
        // The room belongs to both sides; only the customer is the one who booked.
        $worker = User::factory()->create();
        $customer = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $worker->id]);
        $booking = $this->makeWorkBooking($work, $customer);

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/messenger/work-bookings/{$booking->id}/channel")
            ->assertCreated()
            ->assertJsonPath('data.counterpart.id', $customer->id);
    }

    public function test_an_applicant_can_open_the_room_for_their_application(): void
    {
        // The recruit side mirrors the work side: the applicant is the worker,
        // and the listing's owner is the employer.
        $applicant = User::factory()->create();
        $employer = User::factory()->create();
        $recruit = Recruit::factory()->create(['author_id' => $employer->id]);
        $booking = $this->makeRecruitBooking($recruit, $applicant);

        $response = $this->actingAs($applicant, 'sanctum')
            ->postJson("/api/v1/messenger/recruit-bookings/{$booking->id}/channel");

        $response->assertCreated()
            ->assertJsonPath('data.booking_type', 'recruit-booking')
            ->assertJsonPath('data.booking_id', $booking->id)
            ->assertJsonPath('data.counterpart.id', $employer->id);

        $this->assertDatabaseCount('messenger_channels', 1);
        $this->assertDatabaseCount('messenger_participants', 2);

        // The customer flag is inverted here: the employer owns the listing.
        $this->assertDatabaseHas('messenger_participants', [
            'channel_id' => $response->json('data.id'),
            'user_id' => $applicant->id,
            'is_customer' => false,
        ]);
        $this->assertDatabaseHas('messenger_participants', [
            'channel_id' => $response->json('data.id'),
            'user_id' => $employer->id,
            'is_customer' => true,
        ]);
    }

    public function test_opening_the_same_application_twice_returns_the_same_room(): void
    {
        $applicant = User::factory()->create();
        $employer = User::factory()->create();
        $recruit = Recruit::factory()->create(['author_id' => $employer->id]);
        $booking = $this->makeRecruitBooking($recruit, $applicant);

        $first = $this->actingAs($applicant, 'sanctum')
            ->postJson("/api/v1/messenger/recruit-bookings/{$booking->id}/channel")
            ->json('data.id');

        $second = $this->actingAs($employer, 'sanctum')
            ->postJson("/api/v1/messenger/recruit-bookings/{$booking->id}/channel")
            ->json('data.id');

        $this->assertSame($first, $second);
        $this->assertDatabaseCount('messenger_channels', 1);
    }

    public function test_a_stranger_cannot_open_the_room_for_someone_elses_application(): void
    {
        $applicant = User::factory()->create();
        $employer = User::factory()->create();
        $stranger = User::factory()->create();
        $recruit = Recruit::factory()->create(['author_id' => $employer->id]);
        $booking = $this->makeRecruitBooking($recruit, $applicant);

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/messenger/recruit-bookings/{$booking->id}/channel")
            ->assertForbidden();

        $this->assertDatabaseCount('messenger_channels', 0);
    }

    public function test_a_stranger_cannot_open_the_room_for_someone_elses_booking(): void
    {
        $worker = User::factory()->create();
        $customer = User::factory()->create();
        $stranger = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $worker->id]);
        $booking = $this->makeWorkBooking($work, $customer);

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/messenger/work-bookings/{$booking->id}/channel")
            ->assertForbidden();

        $this->assertDatabaseCount('messenger_channels', 0);
    }

    public function test_a_participant_can_send_and_read_messages(): void
    {
        [$channel, $worker, $customer] = $this->makeChannelWithBooking();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/messages", [
                'content' => 'สวัสดีครับ สอบถามรายละเอียดงาน',
                'local_code_id' => 'app-1',
            ])
            ->assertCreated()
            // The sender is looking at their own message, so it reads as theirs.
            ->assertJsonPath('data.is_mine', true)
            ->assertJsonPath('data.local_code_id', 'app-1');

        $response = $this->actingAs($worker, 'sanctum')
            ->getJson("/api/v1/messenger/channels/{$channel->id}/messages");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            // The same message reads as the other side's for the recipient.
            ->assertJsonPath('data.0.is_mine', false)
            ->assertJsonPath('data.0.author.id', $customer->id);
    }

    public function test_after_id_returns_only_what_arrived_since(): void
    {
        [$channel, $worker, $customer] = $this->makeChannelWithBooking();

        $this->sendMessage($customer, $channel->id, 'ข้อความแรก');
        $second = $this->sendMessage($customer, $channel->id, 'ข้อความที่สอง');

        $this->actingAs($customer, 'sanctum')
            ->getJson("/api/v1/messenger/channels/{$channel->id}/messages?after_id={$second->id}")
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_a_stranger_cannot_read_or_write_someone_elses_channel(): void
    {
        [$channel] = $this->makeChannelWithBooking();
        $stranger = User::factory()->create();

        // The channel id is a small sequential integer, so guessing one is the
        // whole attack — every path has to refuse it.
        $this->actingAs($stranger, 'sanctum')
            ->getJson("/api/v1/messenger/channels/{$channel->id}")
            ->assertForbidden();

        $this->actingAs($stranger, 'sanctum')
            ->getJson("/api/v1/messenger/channels/{$channel->id}/messages")
            ->assertForbidden();

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/messages", ['content' => 'แอบส่ง'])
            ->assertForbidden();

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/seen")
            ->assertForbidden();

        $this->assertDatabaseMissing('messenger_conversations', [
            'channel_id' => $channel->id,
            'content' => 'แอบส่ง',
        ]);
    }

    public function test_the_channel_list_shows_only_rooms_the_caller_is_in(): void
    {
        [, $worker, $customer] = $this->makeChannelWithBooking();
        $stranger = User::factory()->create();

        $this->actingAs($worker, 'sanctum')
            ->getJson('/api/v1/messenger/channels')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/messenger/channels')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($stranger, 'sanctum')
            ->getJson('/api/v1/messenger/channels')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_marking_seen_stamps_the_other_sides_messages_only(): void
    {
        [$channel, $worker, $customer] = $this->makeChannelWithBooking();

        $theirs = $this->sendMessage($customer, $channel->id, 'ของลูกค้า');
        $mine = $this->sendMessage($worker, $channel->id, 'ของผู้ดูแล');

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/seen")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $theirs->id);

        // The receipt has to be an actual timestamp, so re-read it rather than
        // asserting on a value the response never carried.
        $this->assertNotNull(
            MessengerConversation::findOrFail($theirs->id)->seen_at,
        );
        // A caller must not be able to clear their own receipts.
        $this->assertNull(
            MessengerConversation::findOrFail($mine->id)->seen_at,
        );
    }

    public function test_a_message_must_carry_content(): void
    {
        [$channel, , $customer] = $this->makeChannelWithBooking();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/messages", ['content' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * A room opened through the real service, with both sides' ids returned.
     *
     * @return array{0: MessengerChannel, 1: User, 2: User}
     */
    private function makeChannelWithBooking(): array
    {
        $worker = User::factory()->create();
        $customer = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $worker->id]);
        $booking = $this->makeWorkBooking($work, $customer);

        $channel = app(MessengerChannelService::class)->forWorkBooking($booking);

        return [$channel, $worker, $customer];
    }

    private function makeWorkBooking(Work $work, User $customer): WorkBooking
    {
        return WorkBooking::create([
            'booking_date' => '2026-10-20',
            'customer_message' => 'อยากจองงานนี้',
            'mobile_phone' => '0812345678',
            'booking_status' => BookingStatus::WAITING_TO_CONFIRM->value,
            'customer_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'worker_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'author_id' => $customer->id,
            'work_id' => $work->id,
        ]);
    }

    private function makeRecruitBooking(Recruit $recruit, User $applicant): RecruitBooking
    {
        // The confirm-status columns default to waiting-to-confirm, so only the
        // ids the route resolves against have to be set.
        return RecruitBooking::create([
            'booking_date' => '2026-10-20',
            'customer_message' => 'ผมสมัครทำงานนี้',
            'mobile_phone' => '0812345678',
            'booking_status' => BookingStatus::WAITING_TO_CONFIRM->value,
            'author_id' => $applicant->id,
            'recruit_id' => $recruit->id,
        ]);
    }

    private function sendMessage(User $user, int $channelId, string $content): MessengerConversation
    {
        return MessengerConversation::create([
            'content' => $content,
            'type' => 'text',
            'local_code_id' => 'test-'.Str::random(8),
            'user_id' => $user->id,
            'channel_id' => $channelId,
        ]);
    }
}
