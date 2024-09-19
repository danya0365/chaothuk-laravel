<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Permission: string
{
    use UsefulEnums;

    case ACCESS_BACKEND = 'access_backend';
    case MANAGE_PERMISSION = 'manage_permission';
    case MANAGE_ROLE = 'manage_role';
    case CREATE_RECRUIT = 'create_recruit';
    case CREATE_WORK = 'create_work';
    case REVIEW_WORK = 'review_work';
    case REPLY_REVIEW = 'reply_review';
}
