<?php

namespace App\Livewire\Frontend;

use App\Models\User;
use App\Models\UserReputation;
use App\Models\UserReputationReview;
use App\Models\UserBadge;
use App\Models\UserVerification;
use Livewire\Component;

class ReputationProfile extends Component
{
    public ?int $userId = null;
    public ?array $reputation = null;
    public array $badges = [];
    public array $verifications = [];
    public array $reviews = [];
    public ?array $user = null;
    public array $ratingDistribution = [];

    public function mount(?int $id = null): void
    {
        $this->userId = $id ?? auth()->id();

        if (!$this->userId) {
            return;
        }

        $user = User::find($this->userId);
        if (!$user) return;

        $this->user = [
            'id'            => $user->id,
            'name'          => $user->name,
            'full_name'     => $user->getFullName(),
            'avatar'        => $user->getAvatar(200),
            'member_since'  => $user->created_at?->format('M Y'),
        ];

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

        // Rating Distribution
        $allReviews = UserReputationReview::where('reviewee_id', $this->userId)->get();
        for ($star = 5; $star >= 1; $star--) {
            $count = $allReviews->where('overall_rating', $star)->count();
            $this->ratingDistribution[$star] = [
                'count'   => $count,
                'percent' => $allReviews->count() > 0
                    ? round(($count / $allReviews->count()) * 100) : 0,
            ];
        }
    }

    public function render()
    {
        return view('livewire.frontend.reputation-profile')
            ->layout('frontend.layout', ['title' => 'Reputation — Chaothuk']);
    }
}
