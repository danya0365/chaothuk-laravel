<?php

namespace Tests\Feature\Api\V1;

use App\Models\Post;
use App\Models\Recruit;
use App\Models\RecruitReview;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    private function reviewer(): User
    {
        // The MEMBER role carries review_work and reply_review, exactly like the
        // seeded accounts the web form already works with.
        $user = User::factory()->create();
        $user->roles()->sync([\App\Enums\Role::MEMBER->value]);

        return $user->fresh();
    }

    public function test_listing_reviews_are_public(): void
    {
        $work = Work::factory()->create();
        $reviewer = $this->reviewer();
        $post = Post::create(['author_id' => $reviewer->id, 'content' => 'ทำงานดีมาก ใช้บริการอีกแน่', 'rating' => 5, 'images' => []]);
        WorkReview::create(['work_id' => $work->id, 'post_id' => $post->id]);

        Post::create(['author_id' => $reviewer->id, 'parent_id' => $post->id, 'content' => 'ขอบคุณครับ', 'images' => []]);

        $this->getJson("/api/v1/works/{$work->id}/reviews")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rating', 5)
            ->assertJsonPath('data.0.replies.0.content', 'ขอบคุณครับ');
    }

    public function test_recruit_reviews_are_public(): void
    {
        $recruit = Recruit::factory()->create();
        $reviewer = $this->reviewer();
        $post = Post::create(['author_id' => $reviewer->id, 'content' => 'รับงานครบตามเวลา', 'rating' => 4, 'images' => []]);
        RecruitReview::create(['recruit_id' => $recruit->id, 'post_id' => $post->id]);

        $this->getJson("/api/v1/recruits/{$recruit->id}/reviews")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.rating', 4);
    }

    public function test_creating_a_review_requires_authentication(): void
    {
        $work = Work::factory()->create();

        $this->postJson("/api/v1/works/{$work->id}/reviews", [])->assertUnauthorized();
    }

    public function test_creating_a_review_requires_the_review_permission(): void
    {
        $withoutRole = User::factory()->create();
        $work = Work::factory()->create();

        $this->actingAs($withoutRole, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/reviews", ['content' => 'ดีมากครับ', 'rating' => 5])
            ->assertForbidden()
            ->assertJsonPath('message', 'คุณไม่มีสิทธิ์รีวิวประกาศ');

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_member_leaves_a_work_review_and_the_owner_is_notified(): void
    {
        $reviewer = $this->reviewer();
        $work = Work::factory()->create();

        $response = $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/reviews", [
                'title' => 'ช่างดีมาก',
                'content' => 'งานเรียบร้อย ตรงเวลา',
                'rating' => 5,
            ]);

        $response->assertCreated();
        $response->assertJsonPath('data.rating', 5);
        $response->assertJsonPath('data.author.name', $reviewer->name);

        $this->assertDatabaseHas('posts', ['content' => 'งานเรียบร้อย ตรงเวลา', 'rating' => 5, 'author_id' => $reviewer->id]);
        $this->assertDatabaseHas('works_reviews', ['work_id' => $work->id, 'post_id' => $response->json('data.id')]);

        // The owner hears about it, pointed at the listing so the app can route there.
        $this->assertDatabaseHas('user_notifications', [
            'author_id' => $work->author_id,
            'notification_type' => 'work-review',
            'notificationable_type' => Work::class,
            'notificationable_id' => $work->id,
        ]);
    }

    public function test_self_review_sends_no_notification(): void
    {
        $reviewer = $this->reviewer();
        $ownWork = Work::factory()->for($reviewer, 'author')->create();

        $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/v1/works/{$ownWork->id}/reviews", ['content' => 'ประกาศตัวเองดีมาก', 'rating' => 5])
            ->assertCreated();

        $this->assertDatabaseCount('user_notifications', 0);
    }

    public function test_review_input_is_validated(): void
    {
        $reviewer = $this->reviewer();
        $work = Work::factory()->create();

        $this->actingAs($reviewer, 'sanctum')
            ->postJson("/api/v1/works/{$work->id}/reviews", ['content' => 'สั้น', 'rating' => 9])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['content', 'rating']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_replying_requires_the_reply_permission(): void
    {
        $reviewer = $this->reviewer();
        $work = Work::factory()->create();
        $review = Post::create(['author_id' => $reviewer->id, 'content' => 'ใช้บริการดีมากนะครับ', 'rating' => 3, 'images' => []]);
        WorkReview::create(['work_id' => $work->id, 'post_id' => $review->id]);

        $stranger = User::factory()->create();

        $this->actingAs($stranger, 'sanctum')
            ->postJson("/api/v1/reviews/{$review->id}/replies", ['content' => 'ขอบคุณมากครับ'])
            ->assertForbidden();

        $this->assertDatabaseCount('posts', 1);
    }

    public function test_the_listing_owner_replies_and_the_reviewer_is_notified(): void
    {
        $reviewer = $this->reviewer();
        $ownerUser = $this->reviewer();
        $owner = Work::factory()->for($ownerUser, 'author')->create();
        $review = Post::create(['author_id' => $reviewer->id, 'content' => 'พอใช้ได้แต่มาช้า', 'rating' => 3, 'images' => []]);
        WorkReview::create(['work_id' => $owner->id, 'post_id' => $review->id]);

        $reply = $this->actingAs($ownerUser, 'sanctum')
            ->postJson("/api/v1/reviews/{$review->id}/replies", ['content' => 'ขออภัยครับจะปรับปรุง'])
            ->assertCreated()
            ->assertJsonPath('data.content', 'ขออภัยครับจะปรับปรุง')
            ->json('data.id');

        // The reply nests under the review, and not as another top-level review.
        $this->assertDatabaseHas('posts', ['id' => $reply, 'parent_id' => $review->id]);
        $this->getJson("/api/v1/works/{$owner->id}/reviews")
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.replies.0.content', 'ขออภัยครับจะปรับปรุง');

        $this->assertDatabaseHas('user_notifications', [
            'author_id' => $reviewer->id,
            'notification_type' => 'review-reply',
            'notificationable_type' => Work::class,
            'notificationable_id' => $owner->id,
        ]);
    }

    public function test_a_reply_to_a_reply_is_refused(): void
    {
        $reviewer = $this->reviewer();
        $work = Work::factory()->create();
        $review = Post::create(['author_id' => $reviewer->id, 'content' => 'งานโอเคระดับกลาง', 'rating' => 3, 'images' => []]);
        WorkReview::create(['work_id' => $work->id, 'post_id' => $review->id]);

        $child = Post::create(['author_id' => $reviewer->id, 'parent_id' => $review->id, 'content' => 'ตอบแล้ว', 'images' => []]);

        $this->actingAs($this->reviewer(), 'sanctum')
            ->postJson("/api/v1/reviews/{$child->id}/replies", ['content' => 'ต่ออีกชั้น'])
            ->assertForbidden()
            ->assertJsonPath('message', 'ตอบกลับได้เฉพาะรีวิวหลักเท่านั้น');
    }
}
