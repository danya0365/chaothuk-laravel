<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionTest extends TestCase
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

    /**
     * The worker side mirrors the web: the listing owner runs the session for a
     * confirmed booking, and starting it pre-confirms their own half.
     */
    private function confirmedBooking(): array
    {
        $worker = User::factory()->create();
        $customer = User::factory()->create();

        $work = Work::factory()->for($worker, 'author')->create();
        $booking = WorkBooking::create([
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'customer_message' => 'จะเอางานตอนบ่าย',
            'mobile_phone' => '0812345678',
            'booking_status' => 'confirm',
            'customer_confirm_status' => 'confirm',
            'worker_confirm_status' => 'confirm',
            'author_id' => $customer->id,
            'work_id' => $work->id,
        ]);

        return [$worker, $customer, $booking];
    }

    public function test_sessions_require_authentication(): void
    {
        $this->getJson('/api/v1/sessions')->assertUnauthorized();
        $this->postJson('/api/v1/sessions/work-bookings/1/start', [])->assertUnauthorized();
    }

    public function test_the_worker_starts_a_session_from_a_confirmed_booking(): void
    {
        [$worker, $customer, $booking] = $this->confirmedBooking();

        $response = $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/work-bookings/{$booking->id}/start", []);

        $response->assertCreated();
        $response->assertJsonPath('data.status', 'active');
        $response->assertJsonPath('data.is_worker', true);
        $this->assertSame(
            (float) $booking->work->price,
            (float) $response->json('data.price_agreed'),
        );

        $this->assertDatabaseHas('work_sessions', [
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $customer->id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'status' => 'active',
            'worker_confirm' => 'confirmed',
            'customer_confirm' => 'pending',
        ]);
    }

    public function test_start_rejects_an_unconfirmed_or_foreign_booking(): void
    {
        [, , $booking] = $this->confirmedBooking();

        // The booking exists but is parked back to waiting — the owner cannot run it.
        $booking->update(['booking_status' => 'waiting-to-confirm']);

        $this->actingAs($booking->work->author, 'sanctum')
            ->postJson("/api/v1/sessions/work-bookings/{$booking->id}/start", [])
            ->assertForbidden()
            ->assertJsonPath('message', 'เริ่มเซสชันได้เฉพาะการจองที่ยืนยันแล้ว');

        // A complete stranger cannot start it either — the ownership check fires first.
        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson("/api/v1/sessions/work-bookings/{$booking->id}/start", [])
            ->assertForbidden();

        $this->assertDatabaseCount('work_sessions', 0);
    }

    public function test_a_booking_runs_one_session_at_a_time(): void
    {
        [$worker, , $booking] = $this->confirmedBooking();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/work-bookings/{$booking->id}/start", [])
            ->assertCreated();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/work-bookings/{$booking->id}/start", [])
            ->assertForbidden()
            ->assertJsonPath('message', 'การจองนี้มีเซสชันที่ยังทำงานอยู่แล้ว');

        $this->assertDatabaseCount('work_sessions', 1);
    }

    public function test_index_scopes_to_the_caller_and_filters_status(): void
    {
        [$worker, $customer, $booking] = $this->confirmedBooking();

        WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $customer->id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        // Someone else's session never shows up.
        $this->actingAs(User::factory()->create(), 'sanctum')
            ->getJson('/api/v1/sessions')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        // Both participants see it, and the status filter narrows it.
        foreach ([$worker, $customer] as $participant) {
            $this->actingAs($participant, 'sanctum')
                ->getJson('/api/v1/sessions')
                ->assertOk()
                ->assertJsonCount(1, 'data')
                ->assertJsonStructure(['data' => [['id', 'status', 'worker' => ['id', 'name'], 'customer' => ['id', 'name']]]]);
        }

        $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/sessions?status=completed')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->actingAs($customer, 'sanctum')
            ->getJson('/api/v1/sessions?status=active')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_show_is_participant_only(): void
    {
        [$worker, $customer, $booking] = $this->confirmedBooking();

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $customer->id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now()->subMinutes(30),
            'status' => 'active',
        ]);

        $this->actingAs($worker, 'sanctum')
            ->getJson("/api/v1/sessions/{$session->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $session->id)
            ->assertJsonPath('data.is_worker', true);

        $this->actingAs($customer, 'sanctum')
            ->getJson("/api/v1/sessions/{$session->id}")
            ->assertOk()
            ->assertJsonPath('data.is_worker', false);

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->getJson("/api/v1/sessions/{$session->id}")
            ->assertForbidden()
            ->assertJsonPath('message', 'ไม่พบเซสชันนี้');
    }

    public function test_pause_resume_and_stop_follow_the_web_transitions(): void
    {
        [$worker, , $booking] = $this->confirmedBooking();

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $booking->author_id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now()->subMinutes(90),
            'status' => 'active',
        ]);

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/pause")
            ->assertOk()
            ->assertJsonPath('data.status', 'paused');

        // Paused sessions accept no GPS.
        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/locations", ['latitude' => 13.75, 'longitude' => 100.5])
            ->assertForbidden();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/resume")
            ->assertOk()
            ->assertJsonPath('data.status', 'active');

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/stop")
            ->assertOk()
            ->assertJsonPath('data.status', 'completed');

        $this->assertDatabaseHas('work_sessions', [
            'id' => $session->id,
            'status' => 'completed',
            'total_duration_minutes' => 90,
        ]);

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/stop")
            ->assertForbidden()
            ->assertJsonPath('message', 'เซสชันนี้จบไปแล้ว');
    }

    public function test_pause_rejects_a_completed_session(): void
    {
        [$worker, , $booking] = $this->confirmedBooking();

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $booking->author_id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now()->subMinutes(60),
            'ended_at' => now(),
            'total_duration_minutes' => 60,
            'status' => 'completed',
        ]);

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/pause")
            ->assertForbidden()
            ->assertJsonPath('message', 'หยุดชั่วคราวได้เฉพาะเซสชันที่กำลังรันอยู่');
    }

    public function test_both_sides_confirm_their_own_half(): void
    {
        [$worker, $customer, $booking] = $this->confirmedBooking();

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $customer->id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/confirm")
            ->assertOk()
            ->assertJsonPath('data.customer_confirm', 'confirmed')
            ->assertJsonPath('data.worker_confirm', 'pending');

        $this->assertDatabaseHas('work_sessions', [
            'id' => $session->id,
            'customer_confirm' => 'confirmed',
            'worker_confirm' => 'pending',
        ]);
    }

    public function test_a_participant_drops_gps_pings_while_active(): void
    {
        [$worker, $customer, $booking] = $this->confirmedBooking();

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $worker->id,
            'customer_id' => $customer->id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now(),
            'status' => 'active',
        ]);

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/locations", [
                'latitude' => 13.7563,
                'longitude' => 100.5018,
                'accuracy' => 12.5,
                'speed' => 1.4,
                'heading' => 90,
            ])
            ->assertCreated()
            ->assertJsonPath('data.latitude', 13.7563);

        $this->assertDatabaseHas('session_location_logs', [
            'session_id' => $session->id,
            'user_id' => $worker->id,
        ]);

        // The customer is a participant too — both sides may trace.
        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/locations", [
                'latitude' => -10.5,
                'longitude' => -20.5,
                'recorded_at' => '2026-03-03 10:00:00',
            ])
            ->assertCreated();

        // A passer-by gets refused.
        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/locations", [
                'latitude' => 1.0,
                'longitude' => 1.0,
            ])
            ->assertForbidden();

        // Bad coordinates are a client bug, not a silent pass.
        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/sessions/{$session->id}/locations", [
                'latitude' => 91,
                'longitude' => 100.5,
            ])
            ->assertUnprocessable();
    }
}
