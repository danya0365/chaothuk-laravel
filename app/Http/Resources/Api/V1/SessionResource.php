<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A work session — the live tracking record for one confirmed booking.
 *
 * `is_worker` is resolved against the caller's token, so the client can label
 * sides without guessing which numeric id is its own.
 *
 * @mixin \App\Models\WorkSession
 */
class SessionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $class = str_replace('App\\Models\\', '', $this->sessionable_type);

        return [
            'id' => $this->id,
            'sessionable_type' => strtolower($class),
            'sessionable_id' => $this->sessionable_id,
            'work' => $this->whenLoaded('sessionable') && $this->sessionable instanceof \App\Models\Work
                ? new WorkResource($this->sessionable)
                : null,
            'bookingable_type' => $this->bookingable_type
                ? strtolower(str_replace('App\\Models\\', '', $this->bookingable_type))
                : null,
            'bookingable_id' => $this->bookingable_id,
            'worker' => new AuthorResource($this->whenLoaded('worker')),
            'customer' => new AuthorResource($this->whenLoaded('customer')),
            'is_worker' => $this->resolveIsWorker($request),
            'started_at' => $this->started_at?->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'total_duration_minutes' => (int) $this->total_duration_minutes,
            'price_agreed' => $this->price_agreed !== null ? (float) $this->price_agreed : null,
            'status' => $this->status,
            'notes' => $this->notes,
            'cancel_reason' => $this->cancel_reason,
            'worker_confirm' => $this->worker_confirm,
            'customer_confirm' => $this->customer_confirm,
            'latest_location' => $this->whenLoaded('latestLocation', fn () => new SessionLocationLogResource($this->latestLocation)),
            'location_logs' => $this->whenLoaded('locationLogs', fn () => SessionLocationLogResource::collection($this->locationLogs)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * The caller is the side doing the work, or the side who booked it.
     */
    private function resolveIsWorker(Request $request): ?bool
    {
        if (! $request->user()) {
            return false;
        }

        $id = $request->user()->id;

        if ($this->worker_id === $id) {
            return true;
        }

        if ($this->customer_id === $id) {
            return false;
        }

        // Both id checks failed: show() guards outsiders already, but a missing
        // user column (deleted account) lands here.
        return null;
    }
}
