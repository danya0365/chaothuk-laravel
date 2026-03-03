<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum IssueType: string
{
    use UsefulEnums;

    case ONE_TIME = 'one_time';
    case REPEAT = 'repeat';
}
