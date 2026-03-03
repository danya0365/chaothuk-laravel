<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum BannerType: string
{
    use UsefulEnums;

    case PRODUCT = 'product';
    case PROMOTION = 'promotion';
    case EXTERNAL_URL = 'external_url';
}
