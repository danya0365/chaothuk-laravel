<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Theme: string
{
    use UsefulEnums;

    case DEFAULT = 'default';
    case LIGHT = 'light';
    case DARK = 'dark';
}
