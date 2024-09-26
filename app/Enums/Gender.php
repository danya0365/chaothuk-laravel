<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Gender: string
{
    use UsefulEnums;

    case MALE = 'male';
    case FEMALE = 'female';
    case UNKNOWN = 'unknown';
}
