<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum ReportType: string
{
    use UsefulEnums;

    case FRAUD = 'fraud';
    case NO_SHOW = 'no_show';
    case DAMAGE = 'damage';
    case HARASSMENT = 'harassment';
    case FAKE_REVIEW = 'fake_review';
    case OVERCHARGE = 'overcharge';
    case OTHER = 'other';
}
