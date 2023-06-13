<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NotificationType extends Enum
{
    const WorkBooking = 'work-booking';
    const BookingConfirm = 'booking-confirm';
    const WorkLike = 'work-like';
    const WorkReview = 'work-review';
    const ReviewReply = 'review-reply';
    const ReviewLike = 'review-like';
    const RecruitBooking = 'recruit-booking';
}
