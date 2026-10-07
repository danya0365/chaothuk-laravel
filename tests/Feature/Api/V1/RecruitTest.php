<?php

namespace Tests\Feature\Api\V1;

use App\Enums\RecruitStatus;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruitTest extends TestCase
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

    public function test_a_visitor_can_browse_recruits(): void
    {
        Recruit::factory()->count(2)->create();

        $this->getJson('/api/v1/recruits')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [['id', 'title', 'budget', 'province', 'author' => ['id', 'name']]],
                'meta' => ['current_page', 'total'],
            ]);
    }

    public function test_recruits_can_be_filtered_by_province(): void
    {
        $inBangkok = Recruit::factory()->create(['province_id' => 1]);
        Recruit::factory()->create(['province_id' => 2]);

        $this->getJson('/api/v1/recruits?province_id=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $inBangkok->id);
    }

    public function test_creating_a_recruit_requires_authentication(): void
    {
        $this->postJson('/api/v1/recruits', [])->assertUnauthorized();
    }

    public function test_a_user_with_permission_can_create_a_recruit(): void
    {
        $user = User::factory()->create();
        $user->roles()->sync([\App\Enums\Role::MEMBER->value]);

        $this->actingAs($user->fresh(), 'sanctum')
            ->postJson('/api/v1/recruits', $this->validRecruitPayload())
            ->assertCreated()
            ->assertJsonPath('data.title', 'ต้องการรถกะบะรับจ้างขนของ');

        $this->assertDatabaseHas('recruits', [
            'title' => 'ต้องการรถกะบะรับจ้างขนของ',
            'author_id' => $user->id,
            'recruit_status' => 'stand-by',
        ]);
    }

    public function test_a_user_without_the_recruit_permission_is_refused(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson('/api/v1/recruits', $this->validRecruitPayload())
            ->assertForbidden();
    }

    public function test_a_worker_can_apply_for_a_recruit(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::STAND_BY->value]);
        $worker = User::factory()->create();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload())
            ->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'booking_status', 'recruit' => ['id']]]);

        $this->assertDatabaseHas('recruit_bookings', [
            'recruit_id' => $recruit->id,
            'author_id' => $worker->id,
            'booking_status' => 'waiting-to-confirm',
        ]);
    }

    public function test_a_user_cannot_apply_to_their_own_recruit(): void
    {
        $employer = User::factory()->create();
        $recruit = Recruit::factory()->create(['author_id' => $employer->id]);

        $this->actingAs($employer, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('recruit_bookings', 0);
    }

    public function test_applying_notifies_the_employer(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::STAND_BY->value]);

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload())
            ->assertCreated();

        $this->assertDatabaseHas('user_notifications', [
            'author_id' => $recruit->author_id,
            'notification_type' => 'recruit-booking',
        ]);
    }

    public function test_a_closed_recruit_cannot_be_applied_to(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::CLOSE->value]);

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload())
            ->assertForbidden();
    }

    public function test_the_application_confirms_once_both_sides_agree(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::STAND_BY->value]);
        $worker = User::factory()->create();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload());

        $bookingId = RecruitBooking::firstOrFail()->id;

        $this->actingAs($recruit->author, 'sanctum')
            ->postJson("/api/v1/recruit-bookings/{$bookingId}/confirm")
            ->assertOk()
            ->assertJsonPath('data.worker_confirm_status', 'confirm');

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/recruit-bookings/{$bookingId}/confirm-as-applicant")
            ->assertOk()
            ->assertJsonPath('data.booking_status', 'confirm');
    }

    public function test_a_stranger_cannot_confirm_someone_elses_application(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::STAND_BY->value]);
        $worker = User::factory()->create();

        $this->actingAs($worker, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload());

        $bookingId = RecruitBooking::firstOrFail()->id;

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson("/api/v1/recruit-bookings/{$bookingId}/confirm")
            ->assertForbidden();
    }

    public function test_a_worker_sees_only_their_own_applications(): void
    {
        $recruit = Recruit::factory()->create(['recruit_status' => RecruitStatus::STAND_BY->value]);
        $mine = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($mine, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload());

        $this->actingAs($other, 'sanctum')
            ->postJson("/api/v1/recruits/{$recruit->id}/bookings", $this->validBookingPayload());

        $this->actingAs($mine, 'sanctum')
            ->getJson('/api/v1/recruit-bookings')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    /**
     * @return array<string, mixed>
     */
    private function validRecruitPayload(): array
    {
        return [
            'title' => 'ต้องการรถกะบะรับจ้างขนของ',
            'description' => 'ต้องการรถกะบะรับจ้างขนของไปส่งที่คลังสินค้า',
            'budget' => 3000,
            'province_id' => 1,
            'work_type_id' => WorkType::first()?->id ?? 1,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validBookingPayload(): array
    {
        return [
            'booking_date' => now()->addWeek()->format('Y-m-d'),
            'customer_message' => 'สนใจงานนี้ครับ พร้อมทำงานทันที',
            'mobile_phone' => '0812345678',
        ];
    }
}
