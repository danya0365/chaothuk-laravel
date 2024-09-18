<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum PersonType: string
{
    use UsefulEnums;

    case NATURAL = 'natural';
    case JURISTIC = 'juristic';
}
