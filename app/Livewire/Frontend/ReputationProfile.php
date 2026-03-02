<?php

namespace App\Livewire\Frontend;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\UserReputation;
use App\Models\UserReputationReview;
use App\Models\UserBadge;
use App\Models\UserVerification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReputationProfile extends Component
{
    public ?int $userId = null;
    public ?array $user = null;
    public ?array $reputation = null;
    public array $badges = [];
    public array $verifications = [];
    public array $reviews = [];
    public array $ratingDistribution = [];
    public array $works = [];
    public array $stats = [];

    public function mount(?int $id = null): void
    {
        $this->userId = $id ?? auth()->id();
        if (!$this->userId) return;

        $user = User::find($this->userId);
        if (!$user) return;

        $this->user = [
            'id'            => $user->id,
            'name'          => $user->name,
            'full_name'     => $user->getFullName(),
            'avatar'        => $user->getAvatar(200),
            'location'      => $user->location ?? null,
            'member_since'  => $user->created_at?->format('M Y'),
            'member_days'   => $user->created_at ? $user->created_at->diffInDays(now()) : 0,
        ];

        // ─── Works Portfolio ─────────────────────────────────────────────
        $this->works = Work::where('author_id', $this->userId)
            ->with(['province', 'workType', 'categories'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn($w) => [
                'id'        => $w->id,
                'title'     => $w->title,
                'price'     => $w->price,
                'image'     => $w->primary_image,
                'province'  => $w->province?->name_th,
                'type'      => $w->workType?->name,
                'rating'    => $w->avg_review_rating,
                'likes'     => $w->like_count ?? 0,
                'status'    => $w->work_status,
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

        // ─── Reputation Reviews (multi-dimensional) ─────────────────────
        $this->reviews = UserReputationReview::where('reviewee_id', $this->userId)
            ->with('reviewer')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'reviewer_name'   => $r->reviewer?->name ?? 'ผู้ใช้',
                'reviewer_avatar' => $r->reviewer?->getAvatar(48) ?? '',
                'overall_rating'  => $r->overall_rating,
                'quality'         => $r->quality_rating,
                'timeliness'      => $r->timeliness_rating,
                'communication'   => $r->communication_rating,
                'professionalism' => $r->professionalism_rating,
                'comment'         => $r->comment,
                'response'        => $r->response,
                'verified'        => $r->is_verified_booking,
                'date'            => $r->created_at?->diffForHumans(),
            ])
            ->toArray();

        // If no reputation reviews, fall back to work review posts
        if (empty($this->reviews)) {
            $this->reviews = DB::table('works_reviews')
                ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
                ->join('users', 'users.id', '=', 'posts.author_id')
                ->whereIn('works_reviews.work_id', $workIds)
                ->whereNull('posts.parent_id')
                ->orderByDesc('posts.created_at')
                ->limit(10)
                ->select('posts.*', 'users.name as reviewer_name', 'users.profile_image as reviewer_avatar')
                ->get()
                ->map(fn($r) => [
                    'reviewer_name'   => $r->reviewer_name,
                    'reviewer_avatar' => $r->reviewer_avatar ?? '',
                    'overall_rating'  => $r->rating ?? 0,
                    'quality'         => 0,
                    'timeliness'      => 0,
                    'communication'   => 0,
                    'professionalism' => 0,
                    'comment'         => $r->content,
                    'response'        => null,
                    'verified'        => false,
                    'date'            => \Carbon\Carbon::parse($r->created_at)->diffForHumans(),
                ])
                ->toArray();
        }

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
        return view('livewire.frontend.reputation-profile')
            ->layout('frontend.layout', ['title' => ($this->user['name'] ?? 'Reputation') . ' — Chaothuk']);
    }
}
