<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Standby()
 * @method static static Busy()
 * @method static static Close()
 */
final class RecruitStatus extends Enum
{
    const Standby = "stand-by";
    const Busy = "busy";
    const Close = "close";
}
