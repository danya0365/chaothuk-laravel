<?php

namespace App\Console\Commands;

use App\Enums\CronLogType;
use App\Enums\CronRepeatType;
use App\Enums\IssueType;
use App\Models\CronLog;
use App\Models\IssuePoint;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyIssuePoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-issue-point';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $todayDate = Carbon::now()->format('j');
        $todayWeekDay = Carbon::now()->format('l');
        $issuePoints = IssuePoint::query()
            ->where('type', IssueType::REPEAT->value)
            ->where(function ($q) use ($todayDate, $todayWeekDay) {
                $q->where(function ($q) use ($todayDate) {
                    $q->whereJsonContains('cron_info', ['repeat_type' => CronRepeatType::AT_DATE_IN_MONTH->value])
                        ->whereJsonContains('cron_info', ['repeat_value' => $todayDate]);
                });
                $q->orWhere(function ($q) use ($todayWeekDay) {
                    $q->whereJsonContains('cron_info', ['repeat_type' => CronRepeatType::AT_WEEKDAY_IN_WEEK->value])
                        ->whereJsonContains('cron_info', ['repeat_value' => $todayWeekDay]);
                });
            })
            ->whereDate('start_at', '<=', $now)
            ->whereDate('end_at', '>=', $now)
            ->get();

        foreach ($issuePoints as $issuePoint) {
            $points = $issuePoint->points;
            $userId = $issuePoint->user_id;
            $userPoint = UserPoint::create([
                'point_received' => $points,
                'point_available' => $points,
                'user_id' => $userId,
                'issue_point_id' => $issuePoint->id,
                'expired_at' => null,
            ]);
            if ($userPoint) {
                $userPointLog = UserPointLog::create([
                    'points' => $points,
                    'user_point_id' => $userPoint->id,
                    'action_user_id' => 1,
                ]);

                $pointTransactionLog = new PointTransactionLog();
                $pointTransactionLog->user_id = $userId;
                $pointTransactionLog->points = $points;
                $pointTransactionLog->user_point_logs = [$userPointLog];
                $pointTransactionLog->transactionable()->associate($issuePoint);
                $pointTransactionLog->save();
                //$userCustomer->transactions()->save($pointTransactionLog);
            }
        }
        CronLog::create(['cron_type' => CronLogType::DAILY->value, 'number_issue_point_executes' => count($issuePoints)]);
    }
}
