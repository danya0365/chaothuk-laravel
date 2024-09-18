<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum NotificationType: string
{
    use UsefulEnums;

    case GENERAL = 'general';
    case SAVING_BENEFIT = 'saving-benefit';
    case LOAN_APPLICATION = 'loan-application';
    case SAVING_APPLICATION = 'saving-application';
}
