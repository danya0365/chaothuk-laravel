<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum BookingStatus: string
{
    use UsefulEnums;

    case WAITING_TO_CONFIRM = 'waiting-to-confirm';
    case CONFIRM = 'confirm';
    case CLOSE = 'close';
    case CANCEL = 'cancel';
}