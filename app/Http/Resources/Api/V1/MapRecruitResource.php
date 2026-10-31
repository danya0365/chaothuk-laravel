<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One pin of the map screen for a recruit listing — someone is hiring, the
 * budget takes the price's place and the bubble carries no rating.
 *
 * @mixin \App\Models\Recruit
 */
class MapRecruitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => (float) $this->budget,
            'image' => image_url($this->primary_image),
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude,
            'province' => $this->province?->name_th,
            'type' => $this->workType?->title,
            'rating' => 0,
        ];
    }
}
