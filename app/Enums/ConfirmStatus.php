<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum ConfirmStatus: string
{
    use UsefulEnums;

    case WAITING_TO_CONFIRM = 'waiting-to-confirm';
    case CONFIRM = 'confirm';
    case REJECTED = 'rejected';
}