<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A direct conversation between the two sides of a booking.
 *
 * `counterpart` is resolved per request: a channel has two participants and the
 * caller is one of them, so the other one is the person the user is talking to.
 * Sending both participants would mean the phone, email and role of whoever is
 * reading the list.
 *
 * @mixin \App\Models\MessengerChannel
 */
class MessengerChannelResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = $request->user()?->id;
        $counterpart = $this->resource->participants
            ->firstWhere('user_id', '!=', $userId);

        $lastMessage = $this->resource->relationLoaded('latestConversations')
            ? $this->resource->latestConversations->first()
            : null;

        return [
            'id' => $this->id,
            'title' => $this->resource->counterpartName($userId),
            'counterpart' => $counterpart ? [
                'id' => $counterpart->author?->id,
                'name' => $counterpart->author?->name,
                'profile_image' => image_url($counterpart->author?->profile_image),
            ] : null,
            'last_message' => $lastMessage ? [
                'content' => $lastMessage->content,
                'created_at' => $lastMessage->created_at?->toIso8601String(),
            ] : null,
            'unread_count' => (int) ($this->unread_count ?? 0),
            'booking_type' => $this->resource->booking_type,
            'booking_id' => $this->resource->booking_id,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
