<?php

namespace App\Console\Commands;

use App\Models\ProvinceTopWork;
use App\Models\Work;
use App\Models\WorkBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateProvinceTopWorks extends Command
{
    protected $signature = 'top-works:calculate {--period= : Period in Y-m format, defaults to current month}';
    protected $description = 'Calculate top work per province for the given month based on bookings, reviews, and likes';

    public function handle(): int
    {
        $period = $this->option('period') ?? now()->format('Y-m');
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $this->info("Calculating top works for period: {$period}");

        // ──────────────────────────────────────────────────────────────
        // Score formula per work in the given month:
        //   booking_score      = bookings_this_month × 10
        //   confirmed_score    = confirmed_bookings × 20
        //   review_score       = reviews_this_month × 5
        //   rating_bonus       = avg_review_rating × 8
        //   like_bonus         = total_likes × 1
        //   TOTAL              = sum of above
        // ──────────────────────────────────────────────────────────────

        // Get all works grouped by province
        $works = Work::whereNotNull('province_id')->get();

        $provinceScores = []; // province_id => [work_id => data]

        foreach ($works as $work) {
            $pid = $work->province_id;

            // Bookings this month
            $bookingCount = WorkBooking::where('work_id', $work->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $confirmedCount = WorkBooking::where('work_id', $work->id)
                ->whereIn('booking_status', ['confirm', 'close'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Reviews this month
            $reviewData = DB::table('works_reviews')
                ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
                ->where('works_reviews.work_id', $work->id)
                ->whereNull('posts.parent_id')
                ->whereBetween('posts.created_at', [$startDate, $endDate])
                ->selectRaw('COUNT(*) as cnt, AVG(posts.rating) as avg_rating')
                ->first();

            $reviewCount = $reviewData->cnt ?? 0;
            $avgRating = $reviewData->avg_rating ?? 0;
            $likeCount = $work->like_count ?? 0;

            // Score
            $totalScore = ($bookingCount * 10)
                        + ($confirmedCount * 20)
                        + ($reviewCount * 5)
                        + ($avgRating * 8)
                        + ($likeCount * 1);

            // Only consider works with any activity
            if ($totalScore > 0) {
                $provinceScores[$pid][$work->id] = [
                    'work_id'         => $work->id,
                    'author_id'       => $work->author_id,
                    'booking_count'   => $bookingCount,
                    'confirmed_count' => $confirmedCount,
                    'review_count'    => $reviewCount,
                    'avg_rating'      => round($avgRating, 1),
                    'like_count'      => $likeCount,
                    'total_score'     => round($totalScore, 1),
                ];
            }
        }

        // Clear existing entries for this period
        ProvinceTopWork::where('period', $period)->delete();

        $inserted = 0;
        foreach ($provinceScores as $provinceId => $worksData) {
            // Sort by score descending, pick top 1
            uasort($worksData, fn($a, $b) => $b['total_score'] <=> $a['total_score']);
            $top = array_values($worksData)[0];

            ProvinceTopWork::create([
                'province_id'     => $provinceId,
                'work_id'         => $top['work_id'],
                'author_id'       => $top['author_id'],
                'period'          => $period,
                'rank'            => 1,
                'booking_count'   => $top['booking_count'],
                'confirmed_count' => $top['confirmed_count'],
                'review_count'    => $top['review_count'],
                'avg_rating'      => $top['avg_rating'],
                'like_count'      => $top['like_count'],
                'total_score'     => $top['total_score'],
            ]);
            $inserted++;
        }

        $this->info("✅ Inserted top work for {$inserted} provinces");

        return Command::SUCCESS;
    }
}
