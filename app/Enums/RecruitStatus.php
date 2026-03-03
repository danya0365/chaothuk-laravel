<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum RecruitStatus: string
{
    use UsefulEnums;

    case STAND_BY = "stand-by";
    case BUSY = "busy";
    case CLOSE = "close";
}