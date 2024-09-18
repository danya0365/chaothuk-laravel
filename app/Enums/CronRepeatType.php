<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum CronRepeatType: string
{
    use UsefulEnums;

    case AT_WEEKDAY_IN_WEEK = 'at_weekday_in_week';
        //case AT_WEEK_IN_MONTH = 'at_week_in_month';
    case AT_DATE_IN_MONTH = 'at_date_in_month';
    //case AT_MONTH_IN_YEAR = 'at_month_in_year';
}
