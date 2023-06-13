<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static WaitingToConfirm()
 * @method static static Confirm()
 * @method static static Reject()
 */
final class ConfirmStatus extends Enum
{
    const WaitingToConfirm = 'waiting-to-confirm';
    const Confirm = 'confirm';
    const Reject = 'reject';
}
