<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserReputation;
use App\Models\UserReputationReview;
use App\Models\UserVerification;
use App\Models\UserReputationLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReputationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏆 Seeding reputation data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_reputation_reviews')->truncate();
        DB::table('user_reputations')->truncate();
        DB::table('user_badges')->truncate();
        DB::table('user_verifications')->truncate();
        DB::table('user_reputation_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::limit(20)->get();
        if ($users->count() < 4) {
            $this->command->warn('⚠️ Need at least 4 users — run DemoSeeder first');
            return;
        }

        // ─── Reputation Profiles ─────────────────────────────────
        $profiles = [
            // Diamond user
            ['trust' => 'diamond', 'overall' => 4.85, 'quality' => 4.9, 'time' => 4.8, 'comm' => 4.85, 'prof' => 4.8,
             'reviews' => 520, 'completed' => 580, 'cancelled' => 5, 'response_rate' => 98, 'resp_min' => 3, 'repeat' => 85],
            // Gold user
            ['trust' => 'gold', 'overall' => 4.42, 'quality' => 4.5, 'time' => 4.3, 'comm' => 4.4, 'prof' => 4.5,
             'reviews' => 130, 'completed' => 165, 'cancelled' => 12, 'response_rate' => 90, 'resp_min' => 8, 'repeat' => 32],
            // Silver user
            ['trust' => 'silver', 'overall' => 4.1, 'quality' => 4.2, 'time' => 4.0, 'comm' => 4.1, 'prof' => 4.0,
             'reviews' => 65, 'completed' => 78, 'cancelled' => 8, 'response_rate' => 82, 'resp_min' => 14, 'repeat' => 12],
            // Bronze user
            ['trust' => 'bronze', 'overall' => 3.72, 'quality' => 3.8, 'time' => 3.6, 'comm' => 3.7, 'prof' => 3.8,
             'reviews' => 28, 'completed' => 35, 'cancelled' => 9, 'response_rate' => 75, 'resp_min' => 20, 'repeat' => 5],
        ];

        foreach ($users->take(min(count($profiles), $users->count())) as $i => $user) {
            $p = $profiles[$i] ?? $profiles[array_key_last($profiles)];
            UserReputation::create([
                'user_id'                => $user->id,
                'overall_score'          => $p['overall'],
                'quality_score'          => $p['quality'],
                'timeliness_score'       => $p['time'],
                'communication_score'    => $p['comm'],
                'professionalism_score'  => $p['prof'],
                'total_reviews'          => $p['reviews'],
                'total_completed_jobs'   => $p['completed'],
                'total_cancelled_jobs'   => $p['cancelled'],
                'completion_rate'        => round($p['completed'] / ($p['completed'] + $p['cancelled']) * 100, 2),
                'response_rate'          => $p['response_rate'],
                'avg_response_minutes'   => $p['resp_min'],
                'repeat_customer_count'  => $p['repeat'],
                'trust_level'            => $p['trust'],
                'total_points_earned'    => $p['completed'] * 10,
            ]);
        }

        // New users — remaining
        foreach ($users->skip(count($profiles)) as $user) {
            $completed = rand(0, 15);
            $cancelled = rand(0, 3);
            UserReputation::create([
                'user_id'              => $user->id,
                'overall_score'        => round(rand(30, 45) / 10, 2),
                'quality_score'        => round(rand(30, 50) / 10, 2),
                'timeliness_score'     => round(rand(30, 50) / 10, 2),
                'communication_score'  => round(rand(30, 50) / 10, 2),
                'professionalism_score'=> round(rand(30, 50) / 10, 2),
                'total_reviews'        => rand(0, 15),
                'total_completed_jobs' => $completed,
                'total_cancelled_jobs' => $cancelled,
                'completion_rate'      => ($completed + $cancelled) > 0
                    ? round($completed / ($completed + $cancelled) * 100, 2) : 0,
                'response_rate'        => rand(50, 95),
                'avg_response_minutes' => rand(5, 60),
                'repeat_customer_count'=> rand(0, 3),
                'trust_level'          => 'new',
            ]);
        }

        // ─── Reviews (300 records) ──────────────────────────────
        $userIds = $users->pluck('id')->toArray();
        $comments = [
            'งานดีมาก ตรงเวลา ประทับใจ',
            'บริการเยี่ยม สินค้าไม่เสียหายเลย',
            'สุภาพ ตรงเวลา แนะนำเลยครับ',
            'ราคาสมเหตุสมผล งานเสร็จเร็ว',
            'ดีมากครับ จะใช้บริการอีก',
            'ถึงเร็วกว่ากำหนด ของครบไม่ขาด',
            'ไม่ตรงเวลา แต่งานเรียบร้อยดี',
            'พอใช้ได้ สินค้าบางชิ้นเสียหายเล็กน้อย',
            'ยอดเยี่ยมมาก มืออาชีพจริงๆ',
            'สะดวกรวดเร็ว ราคาดี',
        ];

        for ($r = 0; $r < 300; $r++) {
            $reviewerId = $userIds[array_rand($userIds)];
            $revieweeId = $userIds[array_rand($userIds)];
            if ($reviewerId === $revieweeId) continue;

            $quality    = rand(3, 5);
            $timeliness = rand(2, 5);
            $comm       = rand(3, 5);
            $prof       = rand(3, 5);

            UserReputationReview::create([
                'reviewer_id'           => $reviewerId,
                'reviewee_id'           => $revieweeId,
                'booking_type'          => rand(0, 1) ? 'work' : 'recruit',
                'quality_rating'        => $quality,
                'timeliness_rating'     => $timeliness,
                'communication_rating'  => $comm,
                'professionalism_rating'=> $prof,
                'overall_rating'        => round(($quality + $timeliness + $comm + $prof) / 4),
                'comment'               => $comments[array_rand($comments)],
                'is_verified_booking'   => rand(0, 1) ? true : false,
                'created_at'            => now()->subDays(rand(1, 365)),
            ]);
        }

        // ─── Badges ──────────────────────────────────────────────
        $badgeTypes = ['fast_responder', 'on_time_king', 'five_star', 'top_earner', 'verified_pro', 'repeat_magnet', 'zero_cancel', 'community_hero'];
        $levels = ['bronze', 'silver', 'gold'];

        // First 4 users get many badges
        foreach ($users->take(4) as $i => $user) {
            $numBadges = max(1, 8 - $i * 2);
            $shuffled = $badgeTypes;
            shuffle($shuffled);
            foreach (array_slice($shuffled, 0, $numBadges) as $badge) {
                UserBadge::create([
                    'user_id'     => $user->id,
                    'badge_type'  => $badge,
                    'badge_level' => $levels[min($i, 2)],
                ]);
            }
        }

        // ─── Verifications ───────────────────────────────────────
        $verTypes = ['phone', 'email', 'id_card', 'driving_license', 'vehicle_registration'];

        // First user = fully verified
        foreach ($verTypes as $vt) {
            UserVerification::create([
                'user_id'           => $users[0]->id,
                'verification_type' => $vt,
                'status'            => 'approved',
                'verified_at'       => now()->subDays(rand(30, 180)),
            ]);
        }

        // 2nd user = partially verified
        foreach (array_slice($verTypes, 0, 3) as $vt) {
            UserVerification::create([
                'user_id'           => $users[1]->id,
                'verification_type' => $vt,
                'status'            => 'approved',
                'verified_at'       => now()->subDays(rand(10, 90)),
            ]);
        }

        // 3rd user = 1 pending
        UserVerification::create([
            'user_id'           => $users[2]->id,
            'verification_type' => 'phone',
            'status'            => 'approved',
        ]);
        UserVerification::create([
            'user_id'           => $users[2]->id,
            'verification_type' => 'id_card',
            'status'            => 'pending',
        ]);

        $this->command->info("✅ Reputation seeded: {$users->count()} users, 300 reviews, badges, verifications");
    }
}
