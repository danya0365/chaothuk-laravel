<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum CouponExpiresType: string
{
    use UsefulEnums;

    case TYPE_1 = 'type_1';
    case TYPE_2 = 'type_2';
    case TYPE_3 = 'type_3';
}
