<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One blocked date of a work — holidays, leave, one-off downtime.
 *
 * @mixin \App\Models\WorkBlockedDate
 */
class WorkBlockedDateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // The model casts the date column to datetime; the client wants the
            // plain day it submitted.
            'blocked_date' => $this->blocked_date->toDateString(),
            'reason' => $this->reason,
        ];
    }
}
