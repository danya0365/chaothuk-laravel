<?php

namespace Tests\Feature\Api\V1;

use App\Models\Recruit;
use App\Models\User;
use App\Models\Work;
use Database\Seeders\GeographySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\ProvinceSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\WorkTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapTest extends TestCase
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

    public function test_the_map_is_public(): void
    {
        $this->getJson('/api/v1/map')->assertOk();
    }

    public function test_works_and_recruits_with_coordinates_surface_as_pins(): void
    {
        $owner = User::factory()->create();
        $pinned = Work::factory()->create([
            'author_id' => $owner->id,
            'latitude' => 13.7563,
            'longitude' => 100.5018,
            'price' => 450,
        ]);
        Recruit::factory()->create([
            'author_id' => $owner->id,
            'latitude' => 13.7,
            'longitude' => 100.48,
            'budget' => 3000,
            'recruit_status' => 'stand-by',
        ]);

        // Positioned off the map and out of the table entirely, both must stay
        // invisible — most importantly the one that simply lacks coordinates.
        Work::factory()->create(['author_id' => $owner->id, 'work_status' => 'busy']);
        Recruit::factory()->create(['author_id' => $owner->id, 'latitude' => 20.0, 'longitude' => 100.0, 'recruit_status' => 'busy']);

        $response = $this->getJson('/api/v1/map');

        $response->assertOk()
            ->assertJsonCount(1, 'data.works')
            ->assertJsonCount(1, 'data.recruits')
            ->assertJsonPath('data.works.0.id', $pinned->id)
            ->assertJsonPath('data.works.0.lat', 13.7563)
            ->assertJsonPath('data.works.0.price', 450)
            ->assertJsonPath('data.recruits.0.price', 3000);
    }

    public function test_the_kind_filter_isolates_one_side_of_the_market(): void
    {
        $owner = User::factory()->create();
        Work::factory()->create(['author_id' => $owner->id, 'latitude' => 13.7, 'longitude' => 100.4, 'work_status' => 'stand-by']);
        Recruit::factory()->create(['author_id' => $owner->id, 'latitude' => 13.7, 'longitude' => 100.4, 'recruit_status' => 'stand-by']);

        $this->getJson('/api/v1/map?kind=work')
            ->assertOk()
            ->assertJsonCount(1, 'data.works')
            ->assertJsonCount(0, 'data.recruits');

        $this->getJson('/api/v1/map?kind=recruit')
            ->assertOk()
            ->assertJsonCount(0, 'data.works')
            ->assertJsonCount(1, 'data.recruits');
    }
}
