<?php

namespace Tests\Feature\Api\V1;

use App\Enums\BookingStatus;
use App\Enums\ConfirmStatus;
use App\Models\MessengerConversation;
use App\Models\PushToken;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Services\ExpoPushService;
use App\Services\MessengerChannelService;
use Database\Seeders\GeographySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\WorkTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class PushTokenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(GeographySeeder::class);
        $this->seed(ProvinceSeeder::class);
        $this->seed(WorkTypeSeeder::class);
    }

    public function test_registering_a_token_requires_authentication(): void
    {
        $this->postJson('/api/v1/push-tokens', ['token' => 'ExponentPushToken[abc123]'])
            ->assertUnauthorized();
    }

    public function test_a_device_registers_its_token_for_the_caller(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/push-tokens', [
                'token' => 'ExponentPushToken[abc123]',
                'platform' => 'ios',
            ]);

        $response->assertCreated()->assertJsonPath('data.token', 'ExponentPushToken[abc123]');

        $this->assertDatabaseHas('push_tokens', [
            'user_id' => $user->id,
            'token' => 'ExponentPushToken[abc123]',
            'platform' => 'ios',
        ]);
    }

    public function test_re_registering_the_same_device_updates_not_duplicates(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/push-tokens', [
                'token' => 'ExponentPushToken[abc123]',
                'platform' => 'ios',
            ])->assertCreated();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/push-tokens', [
                'token' => 'ExponentPushToken[abc123]',
                'platform' => 'android',
            ])->assertCreated();

        $this->assertDatabaseCount('push_tokens', 1);
        $this->assertDatabaseHas('push_tokens', [
            'user_id' => $user->id,
            'platform' => 'android',
        ]);
    }

    public function test_sending_a_message_pushes_to_the_counterpart_devices(): void
    {
        Http::fake([ExpoPushService::ENDPOINT => Http::response(['data' => ['status' => 'ok']], 200)]);

        [$channel, $worker, $customer] = $this->makeChannelWithBooking();

        PushToken::create([
            'user_id' => $worker->id,
            'token' => 'ExponentPushToken[worker-device]',
            'platform' => 'android',
        ]);

        // A stranger's token must never receive someone else's chat.
        PushToken::create([
            'user_id' => User::factory()->create()->id,
            'token' => 'ExponentPushToken[stranger-device]',
            'platform' => 'android',
        ]);

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/messenger/channels/{$channel->id}/messages", [
                'content' => 'สวัสดีครับ คุยเรื่องการจองได้เลย',
            ])->assertCreated();

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'exp.host')
                && collect($request->data())
                    ->pluck('to')
                    ->contains('ExponentPushToken[worker-device]')
                && collect($request->data())
                    ->pluck('to')
                    ->doesntContain('ExponentPushToken[stranger-device]');
        });
    }

    public function test_messages_carry_a_per_channel_unread_count(): void
    {
        [$channel, $worker, $customer] = $this->makeChannelWithBooking();

        $this->sendMessage($customer, $channel->id, 'ยังไม่อ่าน');
        $this->sendMessage($customer, $channel->id, 'อ่านแล้ว')
            ->update(['seen_at' => now()]);

        // The receiver sees two, but only the unseen one counts.
        $this->actingAs($worker, 'sanctum')
            ->getJson('/api/v1/messenger/channels')
            ->assertOk()
            ->assertJsonPath('data.0.unread_count', 1);

        // The room endpoint reports the same count for the in-room badge.
        $this->actingAs($worker, 'sanctum')
            ->getJson("/api/v1/messenger/channels/{$channel->id}")
            ->assertOk()
            ->assertJsonPath('data.unread_count', 1);

        // The total the bottom-tab badge polls.
        $this->actingAs($worker, 'sanctum')
            ->getJson('/api/v1/messenger/unread')
            ->assertOk()
            ->assertJsonPath('data.unread_count', 1);

        // And the sender's own view stays at zero — their messages are not
        // unread activity for themselves.
        $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/messenger/unread')
            ->assertOk()
            ->assertJsonPath('data.unread_count', 0);
    }

    public function test_unread_requires_authentication(): void
    {
        $this->getJson('/api/v1/messenger/unread')->assertUnauthorized();
    }

    /**
     * A room opened through the real service, with both sides' users returned.
     *
     * @return array{0: \App\Models\MessengerChannel, 1: User, 2: User}
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
