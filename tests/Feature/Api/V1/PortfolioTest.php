<?php

namespace Tests\Feature\Api\V1;

use App\Models\Portfolio;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionSeeder::class);
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /**
     * Every entry needs one work type — the web form requires it too.
     */
    private function workTypeId(): int
    {
        return WorkType::query()->firstOrCreate(['title' => 'รถกะบะ'])->id;
    }

    private function validPayload(): array
    {
        return [
            'title' => 'งานขนของย้ายห้อง',
            'description' => 'ย้ายบ้านสองตึก ต้องตุนกล่องให้พล้อม',
            'work_type_id' => $this->workTypeId(),
            'images' => ['portfoliosimg/first.jpg', 'portfoliosimg/second.jpg'],
        ];
    }

    public function test_portfolios_require_authentication(): void
    {
        $this->getJson('/api/v1/portfolios')->assertUnauthorized();
        $this->postJson('/api/v1/portfolios', [])->assertUnauthorized();
        $this->putJson('/api/v1/portfolios/1', [])->assertUnauthorized();
        $this->deleteJson('/api/v1/portfolios/1')->assertUnauthorized();
    }

    public function test_index_lists_the_callers_own_entries(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $mine = Portfolio::create([
            ...$this->validPayload(),
            'user_id' => $owner->id,
        ]);
        Portfolio::create([
            ...$this->validPayload(),
            'user_id' => $stranger->id,
        ]);

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/v1/portfolios')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mine->id)
            ->assertJsonStructure(['data' => [['id', 'title', 'images', 'work_type' => ['id']]]]);
    }

    public function test_the_owner_saves_one_finished_work(): void
    {
        $owner = User::factory()->create();

        $response = $this->actingAs($owner, 'sanctum')
            ->postJson('/api/v1/portfolios', $this->validPayload());

        $response->assertCreated();
        $response->assertJsonPath('data.title', 'งานขนของย้ายห้อง');

        $this->assertDatabaseHas('portfolios', [
            'user_id' => $owner->id,
            'title' => 'งานขนของย้ายห้อง',
        ]);
    }

    public function test_create_validates_like_the_web_form(): void
    {
        $owner = User::factory()->create();

        // A thin title, a one-word description, and no image is client bug.
        $this->actingAs($owner, 'sanctum')
            ->postJson('/api/v1/portfolios', [
                'title' => 'ก',
                'description' => 'สั้น',
                'work_type_id' => $this->workTypeId(),
                'images' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description', 'images']);

        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_show_is_public_for_anyone(): void
    {
        $owner = User::factory()->create();

        $portfolio = Portfolio::create([
            ...$this->validPayload(),
            'user_id' => $owner->id,
        ]);

        // Signed-out customers may still look — a provider's finished work is advertising.
        $this->getJson("/api/v1/portfolios/{$portfolio->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $portfolio->id)
            ->assertJsonPath('data.user.id', $owner->id);
    }

    public function test_update_is_owner_only(): void
    {
        $owner = User::factory()->create();

        $portfolio = Portfolio::create([
            ...$this->validPayload(),
            'user_id' => $owner->id,
        ]);

        $stranger = User::factory()->create();

        $this->actingAs($stranger, 'sanctum')
            ->putJson("/api/v1/portfolios/{$portfolio->id}", $this->validPayload())
            ->assertForbidden()
            ->assertJsonPath('message', 'คุณแก้ไขได้เฉพาะผลงานของตัวเอง');

        $this->actingAs($owner, 'sanctum')
            ->putJson("/api/v1/portfolios/{$portfolio->id}", [
                ...$this->validPayload(),
                'title' => 'งานขนของแก้แล้ว',
            ])
            ->assertOk()
            ->assertJsonPath('data.title', 'งานขนของแก้แล้ว');
    }

    public function test_delete_is_owner_only_and_iso_of_a_second_call(): void
    {
        $owner = User::factory()->create();

        $portfolio = Portfolio::create([
            ...$this->validPayload(),
            'user_id' => $owner->id,
        ]);

        $stranger = User::factory()->create();

        $this->actingAs($stranger, 'sanctum')
            ->deleteJson("/api/v1/portfolios/{$portfolio->id}")
            ->assertForbidden()
            ->assertJsonPath('message', 'คุณลบได้เฉพาะผลงานของตัวเอง');

        $this->actingAs($owner, 'sanctum')
            ->deleteJson("/api/v1/portfolios/{$portfolio->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('portfolios', ['id' => $portfolio->id]);

        // Soft-deleted rows are out of sight — deleting the id again is fine.
        $this->actingAs($owner, 'sanctum')
            ->deleteJson("/api/v1/portfolios/{$portfolio->id}")
            ->assertNoContent();

        // A stranger's missing row keeps its refusal.
        $this->actingAs($owner, 'sanctum')
            ->deleteJson('/api/v1/portfolios/999999')
            ->assertForbidden()
            ->assertJsonPath('message', 'ไม่พบผลงานนี้');
    }
}
