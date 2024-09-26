<?php

namespace App\Console\Commands;

use App\Enums\CronLogType;
use App\Models\CronLog;
use Illuminate\Console\Command;

class EveryMinuteIssuePoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:every-minute-issue-point';

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
        CronLog::create(['cron_type' => CronLogType::EVERY_MINUTE->value, 'number_issue_point_executes' => 0]);
    }
}
