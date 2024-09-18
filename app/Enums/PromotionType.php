<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum PromotionType: string
{
    use UsefulEnums;

    case NORMAL = 'normal';
    case MISSION = 'mission';
}
