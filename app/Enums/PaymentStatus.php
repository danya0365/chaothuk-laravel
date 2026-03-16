<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum PaymentStatus: string
{
    use EnumOptions;

    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
}
