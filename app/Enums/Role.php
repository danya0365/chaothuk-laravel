<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Role: int
{
    use UsefulEnums;

    case SUPERVISOR = 1;
    case BACKEND = 2;
    case CUSTOMER = 3;
    case MERCHANT = 4;
}
