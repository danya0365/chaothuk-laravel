<?php

namespace App\Enums;

enum WalletTransactionType: string
{
    case DEPOSIT = 'deposit';
    case PAYMENT = 'payment';
    case REFUND = 'refund';
    case WITHDRAWAL = 'withdrawal';
    case ADJUSTMENT = 'adjustment';
}
