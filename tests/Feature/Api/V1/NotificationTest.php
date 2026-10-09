<?php

namespace Tests\Feature\Api\V1;

use App\Enums\NotificationType;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
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

    public function test_listing_notifications_requires_authentication(): void
    {
        $this->getJson('/api/v1/notifications')->assertUnauthorized();
    }

    public function test_a_user_sees_only_their_own_notifications(): void
    {
        $user = User::factory()->create();
        $someoneElse = User::factory()->create();

        $mine = $this->makeNotification($user->id, 'งานของฉัน');
        $this->makeNotification($someoneElse->id, 'งานของคนอื่น');

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/notifications');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mine->id)
            ->assertJsonPath('data.0.title', 'งานของฉัน');
    }

    public function test_a_notification_carries_a_thai_label_and_a_short_target_type(): void
    {
        $user = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $user->id]);

        $this->makeNotification($user->id, 'มีการจองงานใหม่', NotificationType::WORK_BOOKING, false, $work);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/notifications')
            ->assertOk()
            ->assertJsonPath('data.0.type_label', 'มีการจองงานใหม่')
            // The morph column holds the full class name; the client gets "work".
            ->assertJsonPath('data.0.target_type', 'work')
            ->assertJsonPath('data.0.target_id', $work->id)
            ->assertJsonPath('data.0.is_read', false);
    }

    public function test_unread_count_counts_only_unread_notifications(): void
    {
        $user = User::factory()->create();

        $this->makeNotification($user->id, 'ยังไม่อ่าน');
        $this->makeNotification($user->id, 'ยังไม่อ่านอีกอัน');
        $this->makeNotification($user->id, 'อ่านแล้ว', null, true);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.unread_count', 2);
    }

    public function test_marking_a_notification_read_clears_it(): void
    {
        $user = User::factory()->create();
        $notification = $this->makeNotification($user->id, 'มีการจองงานใหม่');

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/notifications/{$notification->id}/read")
            ->assertOk()
            ->assertJsonPath('data.is_read', true);

        $this->assertDatabaseHas('user_notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }

    public function test_a_user_cannot_mark_another_users_notification_read(): void
    {
        $user = User::factory()->create();
        $someoneElse = User::factory()->create();
        $theirs = $this->makeNotification($someoneElse->id, 'ของคนอื่น');

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/v1/notifications/{$theirs->id}/read")
            ->assertForbidden();

        $this->assertDatabaseHas('user_notifications', [
            'id' => $theirs->id,
            'is_read' => false,
        ]);
    }

    public function test_marking_all_read_leaves_other_users_alone(): void
    {
        $user = User::factory()->create();
        $someoneElse = User::factory()->create();

        $this->makeNotification($user->id, 'ของฉันที่ยังไม่อ่าน');
        $this->makeNotification($user->id, 'ของฉันที่อ่านแล้ว', null, true);
        $theirs = $this->makeNotification($someoneElse->id, 'ของคนอื่น');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/notifications/read-all');

        $response->assertOk()->assertJsonPath('data.updated', 1);

        $this->assertDatabaseHas('user_notifications', ['id' => $theirs->id, 'is_read' => false]);
    }

    public function test_booking_a_work_leaves_the_owner_a_readable_notification(): void
    {
        // The booking endpoints have always written these; before this endpoint
        // existed the mobile client created them without ever being able to read them.
        $owner = User::factory()->create();
        $customer = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", [
                'booking_date' => '2026-10-20',
                'customer_message' => 'อยากจองงานนี้ครับ',
                'mobile_phone' => '0812345678',
            ])
            ->assertCreated();

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/v1/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.notification_type', NotificationType::WORK_BOOKING->value)
            ->assertJsonPath('data.0.target_id', $work->id);
    }

    /**
     * The morph columns are NOT NULL, so every notification needs a target — tests
     * that do not care about the target get a throwaway work.
     */
    private function makeNotification(
        int $userId,
        string $title,
        ?NotificationType $type = null,
        bool $isRead = false,
        ?Work $work = null,
    ): UserNotification {
        $work ??= Work::factory()->create(['author_id' => $userId]);

        return UserNotification::create([
            'title' => $title,
            'details' => [],
            'notification_type' => ($type ?? NotificationType::GENERAL)->value,
            'is_read' => $isRead,
            'author_id' => $userId,
            'notificationable_type' => Work::class,
            'notificationable_id' => $work->id,
        ]);
    }
}
