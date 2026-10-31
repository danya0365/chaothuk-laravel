<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\NotificationType;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ReviewCollection;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Post;
use App\Models\Recruit;
use App\Models\RecruitReview;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkReview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Reviews and their replies, threaded the way the web's ReviewSection renders
 * them — one Post tree per listing.
 *
 * Reviews always stand on a listing: the pivot tables (works_reviews /
 * recruits_reviews) decide which one, and the reply notification walks the tree
 * back to that listing so the mobile client can route the recipient to it.
 * Both writes need the caller to hold the web's matching permission
 * (review_work / reply_review), so a plain member without the role writes nothing.
 */
class ReviewController extends Controller
{
    /**
     * Reviews left on one work listing, newest first, replies nested to any depth.
     */
    public function workReviews(Request $request, Work $work): ReviewCollection
    {
        return new ReviewCollection(
            $this->reviewsFor($request, 'works_reviews', 'work_id', $work->id)
        );
    }

    /**
     * Reviews left on one recruit listing — same shape, other pivot.
     */
    public function recruitReviews(Request $request, Recruit $recruit): ReviewCollection
    {
        return new ReviewCollection(
            $this->reviewsFor($request, 'recruits_reviews', 'recruit_id', $recruit->id)
        );
    }

    /**
     * Leave a review on a work. The author id comes from the token — the web
     * form works the same way — and the listing owner gets a notification.
     */
    public function storeWorkReview(Request $request, Work $work): ReviewResource
    {
        $this->assertCallerMayReview($request->user());

        $post = $this->storeReview(
            $request,
            $work,
            NotificationType::WORK_REVIEW->value,
        );

        return new ReviewResource($post);
    }

    /**
     * Leave a review on a recruit.
     */
    public function storeRecruitReview(Request $request, Recruit $recruit): ReviewResource
    {
        $this->assertCallerMayReview($request->user());

        $post = $this->storeReview(
            $request,
            $recruit,
            NotificationType::WORK_REVIEW->value,
        );

        return new ReviewResource($post);
    }

    /**
     * Reply to a top-level review. Nested replies of replies are not offered —
     * the thread stays one review, many answers — so a guessed child post simply
     * refuses.
     */
    public function storeReply(Request $request, Post $post): ReviewResource
    {
        $this->assertCallerMayReply($request->user());

        if ($post->parent_id) {
            throw new AccessDeniedHttpException('ตอบกลับได้เฉพาะรีวิวหลักเท่านั้น');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2'],
        ]);

        $reply = Post::create([
            'author_id' => $request->user()->id,
            'parent_id' => $post->id,
            'content' => $validated['content'],
            'images' => [],
        ]);

        // Route the recipient back through the listing the review lives on, so
        // the notification opens the same page the web opens. A reply is never
        // announced to its own writer.
        $listing = $this->listingForPost($post->id);

        if ($post->author_id !== $request->user()->id) {
            $this->notifyReply($post, $reply, $listing);
        }

        $reply->load(['author', 'replies']);

        return new ReviewResource($reply);
    }

    /**
     * The paged top-level review tree of one listing.
     */
    private function reviewsFor(Request $request, string $pivot, string $foreignKey, int $listingId): \Illuminate\Pagination\LengthAwarePaginator
    {
        $postIds = DB::table($pivot)->where($foreignKey, $listingId)->pluck('post_id');

        $walkReplies = function ($query) use (&$walkReplies) {
            $query->with(['author', 'replies' => $walkReplies])->oldest();
        };

        return Post::with(['author', 'replies' => $walkReplies])
            ->whereIn('id', $postIds)
            ->whereNull('parent_id')
            ->withCount('replies')
            ->latest()
            ->paginate(min(max($request->integer('per_page', 10), 1), 50))
            ->withQueryString();
    }

    /**
     * One review, whose pivot row and notification this step handles.
     */
    private function storeReview(Request $request, Model $listing, string $notificationType): Post
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:5'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $post = Post::create([
            'author_id' => $request->user()->id,
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'images' => [],
        ]);

        // The pivot model handles timestamps and soft-delete bookkeeping; matching
        // an existing row is a no-op rather than a second review.
        match (true) {
            $listing instanceof Work => WorkReview::firstOrCreate(['work_id' => $listing->id, 'post_id' => $post->id]),
            $listing instanceof Recruit => RecruitReview::firstOrCreate(['recruit_id' => $listing->id, 'post_id' => $post->id]),
            default => null,
        };
        if ($listing->author_id !== $request->user()->id) {
            UserNotification::create([
                'title' => 'มีรีวิวใหม่',
                'details' => [
                    'review_id' => $post->id,
                    'rating' => $validated['rating'],
                    'content' => $post->content,
                ],
                'notification_type' => $notificationType,
                'is_read' => false,
                'author_id' => $listing->author_id,
                'notificationable_type' => $listing::class,
                'notificationable_id' => $listing->id,
            ]);
        }

        $post->load(['author', 'replies']);

        return $post;
    }

    private function assertCallerMayReview(User $user): void
    {
        if (! $user->isPermission(Permission::REVIEW_WORK->value)) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์รีวิวประกาศ');
        }
    }

    private function assertCallerMayReply(User $user): void
    {
        if (! $user->isPermission(Permission::REPLY_REVIEW->value)) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์ตอบรีวิว');
        }
    }

    /**
     * Which listing a review post is attached to, with the owner id for the
     * permission check — null when the pivot row vanished or hits neither table.
     *
     * @return array{class: class-string, id: int, title: string, author_id: int}|null
     */
    private function listingForPost(int $postId): ?array
    {
        $work = DB::table('works_reviews')->where('post_id', $postId)->value('work_id');

        if ($work) {
            $found = Work::query()->select(['id', 'title', 'author_id'])->find($work);

            return $found ? ['class' => Work::class, 'id' => $found->id, 'title' => $found->title, 'author_id' => $found->author_id] : null;
        }

        $recruit = DB::table('recruits_reviews')->where('post_id', $postId)->value('recruit_id');

        if ($recruit) {
            $found = Recruit::query()->select(['id', 'title', 'author_id'])->find($recruit);

            return $found ? ['class' => Recruit::class, 'id' => $found->id, 'title' => $found->title, 'author_id' => $found->author_id] : null;
        }

        return null;
    }

    private function notifyReply(Post $review, Post $reply, ?array $listing): void
    {
        UserNotification::create([
            'title' => 'มีคนตอบรีวิวของคุณ',
            'details' => [
                'review_id' => $review->id,
                'reply_id' => $reply->id,
                'content' => $reply->content,
                'work_id' => $listing['id'] ?? null,
                'work_title' => $listing['title'] ?? null,
            ],
            'notification_type' => NotificationType::REVIEW_REPLY->value,
            'is_read' => false,
            'author_id' => $review->author_id,
            'notificationable_type' => $listing['class'] ?? null,
            'notificationable_id' => $listing['id'] ?? null,
        ]);
    }
}
