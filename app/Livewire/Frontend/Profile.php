<?php

namespace App\Livewire\Frontend;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\UserReputation;
use App\Models\UserReputationReview;
use App\Models\UserBadge;
use App\Models\UserVerification;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Profile extends Component
{
    use WithPagination;

    public ?int $userId = null;
    public ?array $user = null;
    public ?array $reputation = null;
    public array $badges = [];
    public array $verifications = [];
    public array $ratingDistribution = [];
    public array $works = [];
    public array $stats = [];

    public function mount(?int $id = null): void
    {
        $this->userId = $id ?? auth()->id();
        if (!$this->userId) {
            $this->redirectRoute('frontend.auth.login');
            return;
        }

        $user = User::find($this->userId);
        if (!$user) {
            abort(404);
        }

        $this->user = [
            'id'            => $user->id,
            'name'          => $user->name,
            'full_name'     => $user->getFullName(),
            'avatar'        => $user->getAvatar(300),
            'location'      => $user->location ?? null,
            'biography'     => $user->biography ?? null,
            'member_since'  => $user->created_at?->format('M Y'),
            'member_days'   => $user->created_at ? $user->created_at->diffInDays(now()) : 0,
        ];

        // ─── Works Portfolio ─────────────────────────────────────────────
        $this->works = Work::where('author_id', $this->userId)
            ->with(['province', 'workType', 'activeFeature'])
            ->latest()
            ->get()
            ->map(fn($w) => [
                'id'             => $w->id,
                'title'          => $w->title,
                'price'          => $w->price,
                'image'          => image_url($w->primary_image),
                'province'       => $w->province?->name_th,
                'type'           => $w->workType?->title,
                'rating'         => $w->avg_review_rating,
                'likes'          => $w->like_count ?? 0,
                'status'         => $w->work_status,
                'active_feature' => $w->activeFeature ? $w->activeFeature->toArray() : null,
            ])
            ->toArray();

        // ─── Stats (from real bookings) ──────────────────────────────────
        $workIds = Work::where('author_id', $this->userId)->pluck('id');
        $totalBookings   = WorkBooking::whereIn('work_id', $workIds)->count();
        $confirmedJobs   = WorkBooking::whereIn('work_id', $workIds)->where('booking_status', 'confirm')->count();
        $closedJobs      = WorkBooking::whereIn('work_id', $workIds)->where('booking_status', 'close')->count();
        $cancelledJobs   = WorkBooking::whereIn('work_id', $workIds)->where('booking_status', 'cancel')->count();
        $totalWorks      = Work::where('author_id', $this->userId)->count();
        $totalLikes      = Work::where('author_id', $this->userId)->sum('like_count');
        $completedJobs   = $confirmedJobs + $closedJobs;
        $completionRate  = $totalBookings > 0 ? round(($completedJobs / $totalBookings) * 100, 1) : 0;

        // Reviews count from posts system
        $reviewPosts = DB::table('works_reviews')
            ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
            ->whereIn('works_reviews.work_id', $workIds)
            ->whereNull('posts.parent_id')
            ->count();

        $avgRating = DB::table('works_reviews')
            ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
            ->whereIn('works_reviews.work_id', $workIds)
            ->whereNull('posts.parent_id')
            ->avg('posts.rating') ?? 0;

        $this->stats = [
            'total_works'     => $totalWorks,
            'total_bookings'  => $totalBookings,
            'completed_jobs'  => $completedJobs,
            'cancelled_jobs'  => $cancelledJobs,
            'completion_rate' => $completionRate,
            'total_likes'     => (int) $totalLikes,
            'total_reviews'   => $reviewPosts,
            'avg_rating'      => round($avgRating, 1),
        ];

        // ─── Reputation system data (if exists) ─────────────────────────
        $rep = UserReputation::where('user_id', $this->userId)->first();
        if ($rep) {
            $this->reputation = $rep->toArray();
            $this->reputation['trust_level_label'] = $rep->trust_level_label;
        }

        $this->badges = UserBadge::where('user_id', $this->userId)
            ->get()
            ->map(fn($b) => [
                'type'  => $b->badge_type,
                'level' => $b->badge_level,
                'label' => $b->label,
            ])
            ->toArray();

        $this->verifications = UserVerification::where('user_id', $this->userId)
            ->get()
            ->map(fn($v) => [
                'type'   => $v->verification_type,
                'status' => $v->status,
                'label'  => $v->label,
            ])
            ->toArray();

        // ─── Rating Distribution ────────────────────────────────────────
        $allRepReviews = UserReputationReview::where('reviewee_id', $this->userId)->get();

        // Fall back to work review posts if no rep reviews
        if ($allRepReviews->isEmpty()) {
            $ratingCounts = DB::table('works_reviews')
                ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
                ->whereIn('works_reviews.work_id', $workIds)
                ->whereNull('posts.parent_id')
                ->select('posts.rating', DB::raw('count(*) as cnt'))
                ->groupBy('posts.rating')
                ->pluck('cnt', 'rating')
                ->toArray();
            $totalForDist = array_sum($ratingCounts);
            for ($star = 5; $star >= 1; $star--) {
                $count = $ratingCounts[$star] ?? 0;
                $this->ratingDistribution[$star] = [
                    'count'   => $count,
                    'percent' => $totalForDist > 0 ? round(($count / $totalForDist) * 100) : 0,
                ];
            }
        } else {
            for ($star = 5; $star >= 1; $star--) {
                $count = $allRepReviews->where('overall_rating', $star)->count();
                $this->ratingDistribution[$star] = [
                    'count'   => $count,
                    'percent' => $allRepReviews->count() > 0
                        ? round(($count / $allRepReviews->count()) * 100) : 0,
                ];
            }
        }
    }

    public function render()
    {
        $viewReviews = UserReputationReview::where('reviewee_id', $this->userId)
            ->with('reviewer')
            ->latest()
            ->paginate(10);
            
        // If no reputation reviews, fall back to work review posts
        if ($viewReviews->isEmpty() && isset($this->stats['total_reviews']) && $this->stats['total_reviews'] > 0) {
            $workIds = Work::where('author_id', $this->userId)->pluck('id');
            $viewReviews = Post::join('works_reviews', 'posts.id', '=', 'works_reviews.post_id')
                ->whereIn('works_reviews.work_id', $workIds)
                ->whereNull('posts.parent_id')
                ->with('author')
                ->select('posts.*')
                ->orderByDesc('posts.created_at')
                ->paginate(10);
                
            // Transform post models for the view to match reputation review structure
            $viewReviews->getCollection()->transform(function ($post) {
                return (object)[
                    'reviewer_name'   => $post->author?->name ?? 'ผู้ใช้',
                    'reviewer_avatar' => $post->author?->getAvatar(48) ?? '',
                    'overall_rating'  => $post->rating ?? 0,
                    'quality'         => 0,
                    'timeliness'      => 0,
                    'communication'   => 0,
                    'professionalism' => 0,
                    'comment'         => $post->content,
                    'response'        => null,
                    'verified'        => false,
                    'date'            => $post->created_at?->diffForHumans(),
                ];
            });
        }

        return view('livewire.frontend.profile', [
            'paginatedReviews' => $viewReviews
        ])
            ->layout('frontend.layout', ['title' => ($this->user['name'] ?? 'Profile') . ' — Chaothuk']);
    }
}
