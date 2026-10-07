<?php

namespace Tests\Feature\Api\V1;

use App\Models\Province;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
        // The reference-data factories pick a real province and work type, and
        // provinces are keyed to a geography.
        $this->seed(\Database\Seeders\GeographySeeder::class);
        $this->seed(\Database\Seeders\ProvinceSeeder::class);
        $this->seed(\Database\Seeders\WorkTypeSeeder::class);
    }

    public function test_a_visitor_can_browse_works(): void
    {
        Work::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/works');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [['id', 'title', 'price', 'province', 'author' => ['id', 'name']]],
            'links' => ['first', 'last'],
            'meta' => ['current_page', 'last_page', 'total', 'per_page'],
        ]);
    }

    public function test_works_paginate(): void
    {
        Work::factory()->count(7)->create();

        $response = $this->getJson('/api/v1/works?per_page=3&page=2');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('meta.current_page', 2);
        $response->assertJsonPath('meta.per_page', 3);
    }

    public function test_works_can_be_filtered_by_keyword(): void
    {
        $match = Work::factory()->create(['title' => 'รถกะบะรับจ้างขนของย้ายบ้าน']);
        Work::factory()->create(['title' => 'มอเตอร์ไซค์รับจ้างส่งของด่วน']);

        $response = $this->getJson('/api/v1/works?keyword='.urlencode('กะบะ'));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $match->id);
    }

    public function test_a_work_detail_is_public(): void
    {
        $work = Work::factory()->create();

        $this->getJson("/api/v1/works/{$work->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $work->id);
    }

    public function test_creating_a_work_requires_authentication(): void
    {
        $this->postJson('/api/v1/works', [])->assertUnauthorized();
    }

    public function test_a_user_with_permission_can_create_a_work(): void
    {
        $user = $this->memberWithPermission();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/works', $this->validWorkPayload());

        $response->assertCreated();
        $response->assertJsonPath('data.title', 'รถกะบะรับจ้างขนของ');

        // author_id must come from the token, never from the body.
        $this->assertDatabaseHas('works', [
            'title' => 'รถกะบะรับจ้างขนของ',
            'author_id' => $user->id,
            'work_status' => 'stand-by',
        ]);
    }

    public function test_a_user_without_the_work_permission_is_refused(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/works', $this->validWorkPayload())
            ->assertForbidden();
    }

    public function test_work_creation_validates_input(): void
    {
        $user = $this->memberWithPermission();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/works', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'code', 'description', 'price', 'province_id', 'work_type_id']);
    }

    /**
     * @return array<string, mixed>
     */
    private function validWorkPayload(): array
    {
        return [
            'code' => 'KK-1001',
            'title' => 'รถกะบะรับจ้างขนของ',
            'description' => 'บริการรับจ้างขนของย้ายบ้านด้วยรถกะบะ ราคาถูก',
            'price' => 1500,
            'province_id' => Province::first()?->id ?? 1,
            'work_type_id' => WorkType::first()?->id ?? 1,
        ];
    }

    private function memberWithPermission(): User
    {
        $user = User::factory()->create();
        $user->roles()->sync([\App\Enums\Role::MEMBER->value]);

        return $user->fresh();
    }
}
