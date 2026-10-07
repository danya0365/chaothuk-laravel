<?php

namespace App\Http\Resources\Api\V1;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A booking of a recruit, from the perspective of whoever applied for it.
 *
 * @mixin \App\Models\RecruitBooking
 */
class RecruitBookingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_date' => $this->bookingDate(),
            'booking_status' => $this->booking_status,
            'customer_confirm_status' => $this->customer_confirm_status,
            'worker_confirm_status' => $this->worker_confirm_status,
            'customer_message' => $this->customer_message,
            'mobile_phone' => $this->mobile_phone,
            'recruit' => new RecruitResource($this->whenLoaded('recruit')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * booking_date has no cast on the model, so it can arrive as a string.
     */
    private function bookingDate(): ?string
    {
        $date = $this->booking_date;

        if ($date === null) {
            return null;
        }

        return $date instanceof DateTimeInterface
            ? $date->format('Y-m-d')
            : Carbon::parse($date)->format('Y-m-d');
    }
}
