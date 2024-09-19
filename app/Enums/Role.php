<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Role: int
{
    use UsefulEnums;

    case MEMBER = 3;
    case SUPERVISOR = 1;
    case BACKEND = 2;
    case MOBILE_PHONE = 4;
}