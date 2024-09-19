<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum NotificationType: string
{
    use UsefulEnums;

    case ANNOUNCEMENT = 'announcement';
    case BOOKING_CONFIRM = 'booking-confirm';
    case WORK_LIKE = 'work-like';
    case WORK_REVIEW = 'work-review';
    case REVIEW_REPLY = 'review-reply';
    case REVIEW_LIKE = 'review-like';
    case RECRUIT_BOOKING = 'recruit-booking';
}
