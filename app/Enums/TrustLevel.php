<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum TrustLevel: string
{
    use UsefulEnums;

    case NEW_USER = 'new';
    case BRONZE = 'bronze';
    case SILVER = 'silver';
    case GOLD = 'gold';
    case PLATINUM = 'platinum';
    case DIAMOND = 'diamond';
}
