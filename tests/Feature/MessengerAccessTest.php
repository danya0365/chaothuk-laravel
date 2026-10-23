<?php

namespace Tests\Feature;

use App\Livewire\Frontend\Messenger;
use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\MessengerParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * The messenger had no authorization at all: any logged-in user could open any
 * channel by its integer id, and the guest endpoints had no auth middleware while
 * taking the mobile phone number — the whole credential — in the URL.
 *
 * These tests pin the fixes so the hole cannot be reopened by a later edit.
 */
class MessengerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_cannot_open_a_channel_they_do_not_belong_to(): void
    {
        [$channel] = $this->makeChannel();

        $stranger = User::factory()->create();

        // Livewire's test harness catches component exceptions, so the guarantee is
        // asserted on its effect: the call must not put the channel on screen. In a
        // real request the AccessDeniedHttpException surfaces as a 403.
        Livewire::actingAs($stranger)
            ->test(Messenger::class)
            ->call('openChannel', $channel->id)
            ->assertSet('activeChannelId', null)
            ->assertSet('conversations', null);
    }

    public function test_a_participant_can_open_their_own_channel(): void
    {
        [$channel, $owner] = $this->makeChannel();

        Livewire::actingAs($owner)
            ->test(Messenger::class)
            ->call('openChannel', $channel->id)
            ->assertSet('activeChannelId', $channel->id);
    }

    public function test_opening_a_channel_with_messages_does_not_error(): void
    {
        // The channel view eager-loaded a `user` relation that the model never had,
        // so every participant who opened a chat hit a RelationNotFoundException.
        [$channel, $owner] = $this->makeChannel();

        MessengerConversation::create([
            'type' => 'text',
            'content' => 'สวัสดีครับ',
            'local_code_id' => 'test-msg-1',
            'user_id' => $owner->id,
            'channel_id' => $channel->id,
        ]);

        Livewire::actingAs($owner)
            ->test(Messenger::class)
            ->call('openChannel', $channel->id)
            ->assertHasNoErrors()
            ->assertSet('conversations.0.content', 'สวัสดีครับ')
            ->assertSet('conversations.0.user', $owner->name);
    }

    public function test_a_stranger_cannot_send_into_someone_elses_channel(): void
    {
        [$channel] = $this->makeChannel();

        $stranger = User::factory()->create();

        // $activeChannelId is client-controlled Livewire state, so a forged send is
        // the attack this guards. Assert nothing was written, not that it threw.
        Livewire::actingAs($stranger)
            ->test(Messenger::class)
            ->set('activeChannelId', $channel->id)
            ->set('newMessage', 'ข้อความที่ไม่ควรถูกส่ง')
            ->call('sendMessage');

        $this->assertDatabaseMissing('messenger_conversations', [
            'channel_id' => $channel->id,
            'content' => 'ข้อความที่ไม่ควรถูกส่ง',
        ]);
    }

    public function test_the_guest_messenger_endpoints_are_gone(): void
    {
        // The guest flow took the phone number in the URL and had no auth at all.
        // It was unreachable dead code, so it was removed rather than locked down.
        // Every messenger route is listed so a new one has to be added here
        // deliberately — the point is to notice an unlisted addition, not to
        // assert an exact count.
        $routes = collect(app('router')->getRoutes()->getRoutes())
            ->filter(fn ($route) => str_contains($route->uri(), 'messenger'))
            ->map(fn ($route) => $route->uri())
            ->values();

        $this->assertSame(
            [
                'api/v1/messenger/channels',
                'api/v1/messenger/channels/{channel}',
                'api/v1/messenger/channels/{channel}/messages',
                'api/v1/messenger/channels/{channel}/messages',
                'api/v1/messenger/channels/{channel}/seen',
                'api/v1/messenger/recruit-bookings/{recruitBooking}/channel',
                'api/v1/messenger/work-bookings/{workBooking}/channel',
                'backend/messenger',
                'backend/messenger/{channel}',
                'frontend/messenger',
            ],
            $routes->sort()->values()->all()
        );
    }

    public function test_every_messenger_api_route_requires_authentication(): void
    {
        // The API messenger surface did not exist when the guest flow was
        // removed; its authorization is pinned per-endpoint in MessengerTest,
        // but this guards the middleware itself from being dropped later.
        $routes = collect(app('router')->getRoutes()->getRoutes())
            ->filter(fn ($route) => str_starts_with($route->uri(), 'api/v1/messenger'));

        $this->assertGreaterThan(0, $routes->count());

        foreach ($routes as $route) {
            $this->assertContains(
                'auth:sanctum',
                $route->gatherMiddleware(),
                "Route [{$route->uri()}] is reachable without authentication."
            );
        }
    }

    public function test_no_route_ever_puts_a_phone_number_in_the_url(): void
    {
        foreach (app('router')->getRoutes()->getRoutes() as $route) {
            $this->assertStringNotContainsStringIgnoringCase(
                'mobilephone',
                $route->uri(),
                "Route [{$route->uri()}] leaks a phone number into the URL."
            );
        }
    }

    public function test_a_message_longer_than_the_old_column_limit_survives(): void
    {
        [$channel, $owner] = $this->makeChannel();

        $longMessage = str_repeat('ก', 900);

        MessengerConversation::create([
            'type' => 'text',
            'content' => $longMessage,
            'local_code_id' => 'test-long',
            'user_id' => $owner->id,
            'channel_id' => $channel->id,
        ]);

        $this->assertDatabaseHas('messenger_conversations', [
            'channel_id' => $channel->id,
            'content' => $longMessage,
        ]);
    }

    public function test_a_user_cannot_join_the_same_channel_twice(): void
    {
        [$channel, $owner] = $this->makeChannel();

        $this->expectException(\Illuminate\Database\QueryException::class);

        MessengerParticipant::create([
            'channel_id' => $channel->id,
            'user_id' => $owner->id,
            'is_customer' => true,
        ]);
    }

    /**
     * @return array{0: MessengerChannel, 1: User}
     */
    private function makeChannel(): array
    {
        $owner = User::factory()->create();
        $peer = User::factory()->create();

        $channel = MessengerChannel::create([
            'slug' => 'test-channel-'.uniqid(),
            'title' => 'ทดสอบ',
            'is_direct' => true,
            'total_participants' => 2,
        ]);

        MessengerParticipant::create([
            'channel_id' => $channel->id,
            'user_id' => $owner->id,
            'is_customer' => true,
        ]);

        MessengerParticipant::create([
            'channel_id' => $channel->id,
            'user_id' => $peer->id,
            'is_customer' => false,
        ]);

        return [$channel, $owner];
    }
}
