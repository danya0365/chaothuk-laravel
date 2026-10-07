<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A "work" — something the author offers to do.
 *
 * @mixin \App\Models\Work
 */
class WorkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'details' => $this->details,
            'price' => $this->price !== null ? (float) $this->price : null,
            'primary_image' => image_url($this->primary_image),
            'images' => collect($this->images ?? [])
                ->map(fn ($path) => image_url($path))
                ->values(),
            'work_status' => $this->work_status,
            'display_priority' => $this->display_priority,
            'avg_review_rating' => $this->avg_review_rating !== null ? (float) $this->avg_review_rating : null,
            'like_count' => (int) ($this->like_count ?? 0),
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
