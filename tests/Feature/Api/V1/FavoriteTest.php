<?php

namespace Tests\Feature\Api\V1;

use App\Models\Favorite;
use App\Models\Recruit;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
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

    public function test_bookmarks_require_authentication(): void
    {
        $this->getJson('/api/v1/favorites')->assertUnauthorized();
        $this->postJson('/api/v1/favorites', [])->assertUnauthorized();
    }

    public function test_a_user_saves_and_lists_a_work_bookmark(): void
    {
        $user = User::factory()->create();
        $work = Work::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/favorites', ['type' => 'work', 'id' => $work->id])
            ->assertCreated()
            ->assertJsonPath('data.type', 'work')
            ->assertJsonStructure(['data' => ['favoritable' => ['id', 'title', 'price']]]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_type' => Work::class,
            'favoritable_id' => $work->id,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/favorites')
            ->assertOk()
            ->assertJsonPath('data.0.id', $work->id)
            ->assertJsonStructure(['data' => [['id', 'type', 'favoritable' => ['title', 'province', 'work_type', 'author']]]]);
    }

    public function test_a_user_saves_and_lists_a_recruit_bookmark(): void
    {
        $user = User::factory()->create();
        $recruit = Recruit::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/favorites', ['type' => 'recruit', 'id' => $recruit->id])
            ->assertCreated()
            ->assertJsonPath('data.type', 'recruit');

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/favorites?type=recruit')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.favoritable.id', $recruit->id);

        // The type filter must not leak bookmarks of the other listing.
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/favorites?type=work')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_saving_the_same_listing_twice_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $work = Work::factory()->create();

        $endpoint = fn () => $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/favorites', ['type' => 'work', 'id' => $work->id]);

        $endpoint()->assertCreated();
        $endpoint()->assertCreated();

        $this->assertDatabaseCount('favorites', 1);
    }

    public function test_save_rejects_an_unknown_or_missing_listing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/favorites', ['type' => 'work', 'id' => 9999])
            ->assertForbidden()
            ->assertJsonPath('message', 'ไม่พบประกาศนี้');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/favorites', ['type' => 'like', 'id' => 1])
            ->assertUnprocessable();

        $this->assertDatabaseCount('favorites', 0);
    }

    public function test_a_user_removes_only_their_own_bookmark(): void
    {
        $user = User::factory()->create();
        $stranger = User::factory()->create();
        $work = Work::factory()->create();

        $mine = Favorite::create(['user_id' => $user->id, 'favoritable_type' => Work::class, 'favoritable_id' => $work->id]);
        Favorite::create(['user_id' => $stranger->id, 'favoritable_type' => Work::class, 'favoritable_id' => $work->id]);

        $this->actingAs($stranger, 'sanctum')
            ->deleteJson("/api/v1/favorites/{$mine->id}")
            ->assertForbidden()
            ->assertJsonPath('message', 'ไม่พบรายการโปรดนี้');

        $this->assertDatabaseCount('favorites', 2);

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/favorites/{$mine->id}")
            ->assertNoContent();

        $this->assertDatabaseCount('favorites', 1);
    }

    public function test_remove_is_idempotent(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/v1/favorites/999')
            ->assertForbidden();
    }

    public function test_check_reports_whether_a_listing_is_bookmarked(): void
    {
        $user = User::factory()->create();
        $work = Work::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/favorites/check?type=work&favoritable_id={$work->id}")
            ->assertOk()
            ->assertJsonPath('data.favorited', false)
            ->assertJsonPath('data.id', null);

        Favorite::create(['user_id' => $user->id, 'favoritable_type' => Work::class, 'favoritable_id' => $work->id]);

        $favorite = Favorite::query()->where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/favorites/check?type=work&favoritable_id={$work->id}")
            ->assertOk()
            ->assertJsonPath('data.favorited', true)
            ->assertJsonPath('data.id', $favorite->id);
    }
}
