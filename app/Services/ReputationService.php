<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserReputation;
use App\Models\UserReputationLog;
use App\Models\UserReputationReview;

class ReputationService
{
    /**
     * สูตรคำนวณ Overall Score
     * Quality×35% + Timeliness×25% + Communication×20% + Professionalism×20%
     */
    public function recalculate(int $userId): UserReputation
    {
        $reputation = UserReputation::firstOrCreate(['user_id' => $userId]);
        $scoreBefore = $reputation->overall_score;

        $reviews = UserReputationReview::where('reviewee_id', $userId)->get();

        if ($reviews->count() === 0) {
            return $reputation;
        }

        $quality    = $reviews->avg('quality_rating');
        $timeliness = $reviews->avg('timeliness_rating');
        $communication = $reviews->avg('communication_rating');
        $professionalism = $reviews->avg('professionalism_rating');

        $overall = ($quality * 0.35)
                 + ($timeliness * 0.25)
                 + ($communication * 0.20)
                 + ($professionalism * 0.20);

        $totalCompleted = $reputation->total_completed_jobs;
        $totalCancelled = $reputation->total_cancelled_jobs;
        $totalJobs = $totalCompleted + $totalCancelled;
        $completionRate = $totalJobs > 0 ? ($totalCompleted / $totalJobs) * 100 : 0;

        $reputation->update([
            'quality_score'         => round($quality, 2),
            'timeliness_score'      => round($timeliness, 2),
            'communication_score'   => round($communication, 2),
            'professionalism_score' => round($professionalism, 2),
            'overall_score'         => round($overall, 2),
            'total_reviews'         => $reviews->count(),
            'completion_rate'       => round($completionRate, 2),
            'trust_level'           => $this->determineTrustLevel(
                $reviews->count(), round($overall, 2), round($completionRate, 2)
            ),
        ]);

        // Log the change
        if ($scoreBefore != $reputation->overall_score) {
            UserReputationLog::create([
                'user_id'      => $userId,
                'event_type'   => 'REVIEW_RECALCULATED',
                'score_before' => $scoreBefore,
                'score_after'  => $reputation->overall_score,
                'metadata'     => ['total_reviews' => $reviews->count()],
            ]);
        }

        return $reputation;
    }

    /**
     * กำหนด Trust Level ตามจำนวนรีวิว + คะแนน + completion rate
     */
    public function determineTrustLevel(int $totalReviews, float $score, float $completionRate): string
    {
        if ($totalReviews >= 500 && $score >= 4.7 && $completionRate >= 98) return 'diamond';
        if ($totalReviews >= 200 && $score >= 4.5 && $completionRate >= 95) return 'platinum';
        if ($totalReviews >= 100 && $score >= 4.3 && $completionRate >= 90) return 'gold';
        if ($totalReviews >= 50  && $score >= 4.0 && $completionRate >= 80) return 'silver';
        if ($totalReviews >= 20  && $score >= 3.5 && $completionRate >= 70) return 'bronze';
        return 'new';
    }

    /**
     * เพิ่มจำนวนงานเสร็จ
     */
    public function incrementCompleted(int $userId): void
    {
        $reputation = UserReputation::firstOrCreate(['user_id' => $userId]);
        $reputation->increment('total_completed_jobs');
        $this->recalculate($userId);
    }

    /**
     * เพิ่มจำนวนงานยกเลิก
     */
    public function incrementCancelled(int $userId): void
    {
        $reputation = UserReputation::firstOrCreate(['user_id' => $userId]);
        $reputation->increment('total_cancelled_jobs');
        $this->recalculate($userId);
    }
}
