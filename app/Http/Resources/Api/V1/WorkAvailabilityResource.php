<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One weekly slot of a work's availability schedule.
 *
 * The web form always shows the seven weekdays, so a missing row is reported
 * as an inactive slot carrying the default hours rather than omitted — the
 * client paints the full week without joining queries.
 *
 * @mixin \App\Models\WorkAvailability
 */
class WorkAvailabilityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day_of_week' => (int) $this->day_of_week,
            'start_time' => substr((string) $this->start_time, 0, 5),
            'end_time' => substr((string) $this->end_time, 0, 5),
            'is_available' => (bool) $this->is_available,
        ];
    }
}
