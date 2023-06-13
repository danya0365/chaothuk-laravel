<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WaitingToConfirm()
 * @method static static Confirm()
 * @method static static Cancel()
 */
final class BookingStatus extends Enum
{
    const WaitingToConfirm = 'waiting-to-confirm';
    const Confirm = 'confirm';
    const Close = 'close';
    const Cancel = 'cancel';
}
