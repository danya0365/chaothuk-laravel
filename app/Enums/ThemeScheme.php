<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum ThemeScheme: string
{
    use UsefulEnums;

    case GREEN = 'green';
    case YELLOW = 'yellow';
}
