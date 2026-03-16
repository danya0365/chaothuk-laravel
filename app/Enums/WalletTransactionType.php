<?php

namespace App\Enums;

use App\Traits\EnumOptions;

enum WalletTransactionType: string
{
    use EnumOptions;

    case DEPOSIT = 'deposit';
    case PAYMENT = 'payment';
    case REFUND = 'refund';
    case WITHDRAWAL = 'withdrawal';
    case ADJUSTMENT = 'adjustment';
}
