<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'customer_message' => $this->customer_message,
            'mobile_phone' => $this->mobile_phone,
            'booking_date' => $this->booking_date,
            'booking_status' => $this->booking_status,
            'customer_confirm_status' => $this->customer_confirm_status,
            'worker_confirm_status' => $this->worker_confirm_status,
            'created_at' => $this->created_at,
            'author' => new UserResource($this->author),
            'recruit' => new RecruitResource($this->recruit),
        ];
    }
}
