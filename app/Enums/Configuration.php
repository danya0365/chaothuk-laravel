<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Configuration: string
{
    use UsefulEnums;

    case COMPANY_NAME = 'company_name';
    case THEME_SCHEME = 'theme_scheme';
}
