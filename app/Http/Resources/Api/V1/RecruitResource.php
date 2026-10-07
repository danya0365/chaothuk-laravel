<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A "recruit" — something the author needs someone to do.
 *
 * Mirrors WorkResource field for field so the mobile client can render both sides
 * of the marketplace with one card component.
 *
 * @mixin \App\Models\Recruit
 */
class RecruitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'details' => $this->details,
            'budget' => $this->budget !== null ? (float) $this->budget : null,
            'primary_image' => image_url($this->primary_image),
            'images' => collect($this->images ?? [])
                ->map(fn ($path) => image_url($path))
                ->values(),
            'recruit_status' => $this->recruit_status,
            'display_priority' => $this->display_priority,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'work_type' => new WorkTypeResource($this->whenLoaded('workType')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
