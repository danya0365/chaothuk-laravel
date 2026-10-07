<?php

namespace Tests\Feature\Api\V1;

use App\Enums\WorkStatus;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkBookingTest extends TestCase
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

    public function test_booking_requires_authentication(): void
    {
        $work = Work::factory()->create();

        $this->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload())
            ->assertUnauthorized();
    }

    public function test_a_customer_can_book_a_work(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $response = $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $response->assertCreated();
        // The old endpoint returned only {status:true} with no id.
        $response->assertJsonStructure([
            'data' => ['id', 'booking_status', 'booking_date', 'work' => ['id', 'title'], 'author' => ['id']],
        ]);

        $this->assertDatabaseHas('work_bookings', [
            'work_id' => $work->id,
            'author_id' => $customer->id,
            'booking_status' => 'waiting-to-confirm',
        ]);
    }

    public function test_booking_notifies_the_worker(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload())
            ->assertCreated();

        $this->assertDatabaseHas('user_notifications', [
            'author_id' => $work->author_id,
            'notification_type' => 'work-booking',
        ]);
    }

    public function test_a_user_cannot_book_their_own_work(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        $this->actingAs($owner, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('work_bookings', 0);
    }

    public function test_a_closed_work_cannot_be_booked(): void
    {
        $work = Work::factory()->create(['work_status' => WorkStatus::CLOSE->value]);
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload())
            ->assertForbidden();
    }

    public function test_booking_validates_the_date_format(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", [
                'booking_date' => now()->addDay()->format('Y-m-d H:i:s'),
                'customer_message' => 'ต้องการขนของย้ายบ้าน',
                'mobile_phone' => '0812345678',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['booking_date']);
    }

    public function test_the_worker_confirming_moves_the_booking_forward(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload())
            ->assertCreated();

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($work->author, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/confirm")
            ->assertOk()
            ->assertJsonPath('data.worker_confirm_status', 'confirm');

        // One side confirmed only — the booking is not confirmed yet.
        $this->assertDatabaseHas('work_bookings', [
            'id' => $bookingId,
            'booking_status' => 'waiting-to-confirm',
        ]);
    }

    public function test_the_booking_confirms_once_both_sides_agree(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($work->author, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/confirm")
            ->assertOk();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/confirm-as-customer")
            ->assertOk()
            ->assertJsonPath('data.booking_status', 'confirm');
    }

    public function test_a_stranger_cannot_confirm_someone_elses_booking(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/confirm")
            ->assertForbidden();
    }

    public function test_the_customer_cannot_confirm_as_the_worker(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/confirm")
            ->assertForbidden();
    }

    public function test_a_customer_can_cancel_their_booking(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/work-bookings/{$bookingId}/cancel")
            ->assertOk()
            ->assertJsonPath('data.booking_status', 'cancel');
    }

    public function test_a_stranger_cannot_read_a_booking(): void
    {
        $work = Work::factory()->create();
        $customer = User::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/bookings", $this->validBookingPayload());

        $bookingId = \App\Models\WorkBooking::firstOrFail()->id;

        $this->actingAs($stranger, 'sanctum')
            ->getJson("/api/v1/work-bookings/{$bookingId}")
            ->assertForbidden();
    }

    public function test_a_customer_sees_only_their_own_bookings(): void
    {
        $mine = Work::factory()->create();
        $theirs = Work::factory()->create();

        $me = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($me, 'sanctum')
            ->postJson("/api/v1/works/{$mine->id}/bookings", $this->validBookingPayload());

        $this->actingAs($other, 'sanctum')
            ->postJson("/api/v1/works/{$theirs->id}/bookings", $this->validBookingPayload());

        $this->actingAs($me, 'sanctum')
            ->getJson('/api/v1/work-bookings')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.work.id', $mine->id);
    }

    /**
     * @return array<string, mixed>
     */
    private function validBookingPayload(): array
    {
        return [
            'booking_date' => now()->addWeek()->format('Y-m-d'),
            'customer_message' => 'ต้องการขนของย้ายบ้าน ช่วยติดต่อกลับด้วย',
            'mobile_phone' => '0812345678',
        ];
    }
}
