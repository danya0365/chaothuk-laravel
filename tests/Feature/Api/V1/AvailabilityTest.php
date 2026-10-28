<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkAvailability;
use App\Models\WorkBlockedDate;
use Database\Seeders\GeographySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\WorkTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
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

    public function test_reading_the_schedule_requires_authentication(): void
    {
        $work = Work::factory()->create();

        $this->getJson("/api/v1/works/{$work->id}/availability")
            ->assertUnauthorized();
    }

    public function test_a_stranger_cannot_read_or_write_someone_elses_schedule(): void
    {
        $work = Work::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($stranger, 'sanctum')
            ->getJson("/api/v1/works/{$work->id}/availability")
            ->assertForbidden();

        $this->actingAs($stranger, 'sanctum')
            ->putJson("/api/v1/works/{$work->id}/availability", ['schedule' => []])
            ->assertForbidden();

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/availability/blocked-dates", [
                'blocked_date' => now()->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_show_renders_the_full_week_even_where_no_row_exists(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        // Only one day was ever configured — the other six must still come
        // back, as inactive slots carrying the default hours.
        WorkAvailability::create([
            'work_id' => $work->id,
            'day_of_week' => 3,
            'start_time' => '09:00',
            'end_time' => '15:00',
            'is_available' => true,
        ]);

        $this->actingAs($owner, 'sanctum')
            ->getJson("/api/v1/works/{$work->id}/availability")
            ->assertOk()
            ->assertJsonPath('data.work_id', $work->id)
            ->assertJsonCount(7, 'data.schedule')
            ->assertJsonPath('data.schedule.3.is_available', true)
            ->assertJsonPath('data.schedule.3.start_time', '09:00')
            // Monday was never configured: inactive with default hours.
            ->assertJsonPath('data.schedule.1.is_available', false)
            ->assertJsonPath('data.schedule.1.start_time', '08:00')
            ->assertJsonPath('data.schedule.1.end_time', '17:00')
            ->assertJsonCount(0, 'data.blocked_dates');
    }

    public function test_put_replaces_the_whole_week_schedule(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        WorkAvailability::create([
            'work_id' => $work->id,
            'day_of_week' => 3,
            'start_time' => '09:00',
            'end_time' => '15:00',
            'is_available' => true,
        ]);

        // Monday and Friday arrive, Wednesday stops arriving — its row is gone.
        $this->actingAs($owner, 'sanctum')
            ->putJson("/api/v1/works/{$work->id}/availability", [
                'schedule' => [
                    [
                        'day_of_week' => 1,
                        'start_time' => '08:00',
                        'end_time' => '17:00',
                        'is_available' => true,
                    ],
                    [
                        'day_of_week' => 2,
                        'start_time' => '10:00',
                        'end_time' => '18:00',
                        'is_available' => false,
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonCount(2, 'data.schedule');

        $this->assertDatabaseMissing('work_availabilities', [
            'work_id' => $work->id,
            'day_of_week' => 3,
        ]);
        $this->assertDatabaseHas('work_availabilities', [
            'work_id' => $work->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'is_available' => true,
        ]);
        $this->assertDatabaseHas('work_availabilities', [
            'work_id' => $work->id,
            'day_of_week' => 2,
            'is_available' => false,
        ]);
    }

    public function test_put_rejects_an_invalid_day_or_time(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        $this->actingAs($owner, 'sanctum')
            ->putJson("/api/v1/works/{$work->id}/availability", [
                'schedule' => [
                    [
                        'day_of_week' => 7,
                        'start_time' => '08:00',
                        'end_time' => '17:00',
                        'is_available' => true,
                    ],
                ],
            ])
            ->assertUnprocessable();

        $this->actingAs($owner, 'sanctum')
            ->putJson("/api/v1/works/{$work->id}/availability", [
                'schedule' => [
                    [
                        'day_of_week' => 1,
                        'start_time' => '8 นาฬิกา',
                        'end_time' => '17:00',
                        'is_available' => true,
                    ],
                ],
            ])
            ->assertUnprocessable();
    }

    public function test_a_blocked_date_needs_a_date_of_today_or_later(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        $this->actingAs($owner, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/availability/blocked-dates", [
                'blocked_date' => now()->subDay()->toDateString(),
            ])
            ->assertUnprocessable();
    }

    public function test_adding_blocked_dates_creates_once_and_keeps_the_first_reason(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);
        $date = now()->addDays(3)->toDateString();

        $this->actingAs($owner, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/availability/blocked-dates", [
                'blocked_date' => $date,
                'reason' => 'ลากิจ',
            ])
            ->assertCreated()
            ->assertJsonPath('data.blocked_date', $date)
            ->assertJsonPath('data.reason', 'ลากิจ');

        // The same date again is a no-op — the row is reused, the original
        // reason survives, and the answer is 200 rather than a new resource.
        $this->actingAs($owner, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/availability/blocked-dates", [
                'blocked_date' => $date,
                'reason' => 'เหตุผลใหม่',
            ])
            ->assertOk()
            ->assertJsonPath('data.reason', 'ลากิจ');

        $this->assertDatabaseCount('work_blocked_dates', 1);
    }

    public function test_show_only_lists_blocked_dates_from_today_on(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);

        WorkBlockedDate::create([
            'work_id' => $work->id,
            'blocked_date' => now()->subDays(2)->toDateString(),
            'reason' => 'อดีต',
        ]);
        WorkBlockedDate::create([
            'work_id' => $work->id,
            'blocked_date' => now()->addDays(5)->toDateString(),
            'reason' => 'วันหยุด',
        ]);

        $this->actingAs($owner, 'sanctum')
            ->getJson("/api/v1/works/{$work->id}/availability")
            ->assertOk()
            ->assertJsonCount(1, 'data.blocked_dates')
            ->assertJsonPath('data.blocked_dates.0.reason', 'วันหยุด');
    }

    public function test_removing_a_blocked_date_is_scoped_to_the_work(): void
    {
        $owner = User::factory()->create();
        $work = Work::factory()->create(['author_id' => $owner->id]);
        $other = Work::factory()->create(['author_id' => $owner->id]);

        $blocked = WorkBlockedDate::create([
            'work_id' => $other->id,
            'blocked_date' => now()->addDays(5)->toDateString(),
        ]);

        // A blocked date of another one of the owner's works does not match
        // this work's path — the delete is silently a no-op.
        $this->actingAs($owner, 'sanctum')
            ->deleteJson("/api/v1/works/{$work->id}/availability/blocked-dates/{$blocked->id}")
            ->assertNoContent();

        $this->assertDatabaseHas('work_blocked_dates', ['id' => $blocked->id]);

        $own = WorkBlockedDate::create([
            'work_id' => $work->id,
            'blocked_date' => now()->addDays(5)->toDateString(),
        ]);

        $this->actingAs($owner, 'sanctum')
            ->deleteJson("/api/v1/works/{$work->id}/availability/blocked-dates/{$own->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('work_blocked_dates', ['id' => $own->id]);
    }
}
