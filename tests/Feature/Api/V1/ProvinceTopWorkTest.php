<?php

namespace Tests\Feature\Api\V1;

use App\Models\Province;
use App\Models\ProvinceTopWork;
use App\Models\User;
use App\Models\Work;
use Database\Seeders\GeographySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\WorkTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvinceTopWorkTest extends TestCase
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

    public function test_the_strip_is_public(): void
    {
        $this->postJson('/api/v1/top-works')->assertMethodNotAllowed();

        $this->getJson('/api/v1/top-works')->assertOk();
    }

    public function test_current_month_rank_one_works_surface_scored_first(): void
    {
        $author = User::factory()->create();
        $provinces = Province::query()->orderBy('id')->limit(2)->get();
        $workA = Work::factory()->create(['author_id' => $author->id, 'price' => 900]);
        $workB = Work::factory()->create(['author_id' => $author->id, 'price' => 350]);

        ProvinceTopWork::create([
            'province_id' => $provinces[0]->id,
            'work_id' => $workA->id,
            'author_id' => $author->id,
            'period' => now()->format('Y-m'),
            'rank' => 1,
            'booking_count' => 6,
            'avg_rating' => 4.5,
            'total_score' => 88.5,
        ]);
        ProvinceTopWork::create([
            'province_id' => $provinces[1]->id,
            'work_id' => $workB->id,
            'author_id' => $author->id,
            'period' => now()->format('Y-m'),
            'rank' => 1,
            'booking_count' => 2,
            'avg_rating' => 4.0,
            'total_score' => 61,
        ]);

        // A runner-up of the same month and a leftover from the past month
        // both stay home — the strip is rank-one-per-province, this month.
        ProvinceTopWork::create([
            'province_id' => $provinces[0]->id,
            'work_id' => $workB->id,
            'author_id' => $author->id,
            'period' => now()->format('Y-m'),
            'rank' => 2,
        ]);
        ProvinceTopWork::create([
            'province_id' => $provinces[1]->id,
            'work_id' => $workA->id,
            'author_id' => $author->id,
            // Start-of-month hops months safely — a plain subMonth() on the
            // 31st can land in the same period the strip reads.
            'period' => now()->startOfMonth()->subMonth()->format('Y-m'),
            'rank' => 1,
        ]);

        $response = $this->getJson('/api/v1/top-works');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.work_id', $workA->id)
            ->assertJsonPath('data.0.score', 88.5)
            ->assertJsonPath('data.0.province', $provinces[0]->name_th)
            ->assertJsonPath('data.1.work_id', $workB->id);
    }
}
