<?php

namespace App\Http\Resources\Api\V1;

use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A notification addressed to one user.
 *
 * `details` is a free-form json column the writing code fills per notification
 * type, so it is passed through as-is — the mobile client reads the keys it knows
 * about (work_id, booking_id, ...) and ignores the rest. The morph target is
 * reduced to a class name so the client can route without loading the record.
 *
 * @mixin \App\Models\UserNotification
 */
class UserNotificationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'details' => $this->details ?? [],
            'notification_type' => $this->notification_type,
            'type_label' => $this->typeLabel(),
            'is_read' => (bool) $this->is_read,
            'target_type' => $this->targetType(),
            'target_id' => $this->notificationable_id,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * A Thai label per type, so the client renders text instead of a raw slug.
     */
    private function typeLabel(): string
    {
        return match ($this->notification_type) {
            NotificationType::WORK_BOOKING->value => 'มีการจองงานใหม่',
            NotificationType::RECRUIT_BOOKING->value => 'มีคนสมัครงานใหม่',
            NotificationType::WORK_BOOKING_CONFIRM->value => 'ยืนยันการจองงานแล้ว',
            NotificationType::RECRUIT_BOOKING_CONFIRM->value => 'ผู้ว่าจ้างเลือกคุณแล้ว',
            NotificationType::WORK_LIKE->value => 'มีคนถูกใจประกาศของคุณ',
            NotificationType::WORK_REVIEW->value => 'มีรีวิวใหม่',
            NotificationType::REVIEW_REPLY->value => 'มีคนตอบรีวิวของคุณ',
            NotificationType::REVIEW_LIKE->value => 'มีคนถูกใจรีวิวของคุณ',
            NotificationType::ANNOUNCEMENT->value => 'ประกาศจากระบบ',
            default => 'การแจ้งเตือน',
        };
    }

    /**
     * "App\Models\Work" → "work", so the client gets a stable short key.
     */
    private function targetType(): ?string
    {
        if (! $this->notificationable_type) {
            return null;
        }

        $class = str_replace('App\\Models\\', '', $this->notificationable_type);

        return strtolower($class);
    }
}
