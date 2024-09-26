<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum IssueStatus: string
{
    use UsefulEnums;

    case SUBMIT = 'submit';
    case APPROVE = 'approve';
    case REJECT = 'reject';
}
