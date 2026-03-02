<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum BadgeType: string
{
    use UsefulEnums;

    case FAST_RESPONDER = 'fast_responder';
    case ON_TIME_KING = 'on_time_king';
    case FIVE_STAR = 'five_star';
    case TOP_EARNER = 'top_earner';
    case VERIFIED_PRO = 'verified_pro';
    case REPEAT_MAGNET = 'repeat_magnet';
    case ZERO_CANCEL = 'zero_cancel';
    case COMMUNITY_HERO = 'community_hero';
}
