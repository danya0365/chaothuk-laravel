<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum MissionStatus: string
{
    use UsefulEnums;

    case IN_PROGRESS = 'in_progress';
    case COMPLETE = 'complete';
    case CANCEL = 'cancel';
}
