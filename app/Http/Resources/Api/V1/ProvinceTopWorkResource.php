<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One card of the home screen's provincial top-works strip — the fields the
 * web front page's "งานเด่นประจำจังหวัด" card shows.
 *
 * @mixin \App\Models\ProvinceTopWork
 */
class ProvinceTopWorkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $work = $this->work;

        return [
            'work_id' => $work?->id,
            'title' => $work?->title,
            'price' => $work !== null ? (float) $work->price : null,
            'image' => image_url($work?->primary_image),
            'province' => $this->province?->name_th,
            'type' => $work?->workType?->title,
            'rating' => (float) $this->avg_rating,
            'score' => (float) $this->total_score,
            'bookings' => (int) $this->booking_count,
            'author_name' => $work?->author?->name,
            'author_avatar' => $work?->author?->getAvatar(32),
        ];
    }
}
