<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:daily-issue-point')->daily();
//Schedule::command('app:every-minute-issue-point')->everyMinute();