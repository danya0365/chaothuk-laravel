<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One finished work shown on a provider's portfolio.
 *
 * The stored image paths are turned into full URLs the same way work images
 * are, so the client never needs to know where the disk lives.
 *
 * @mixin \App\Models\Portfolio
 */
class PortfolioResource extends JsonResource
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
            'images' => collect($this->images ?? [])
                ->map(fn ($path) => image_url($path))
                ->values(),
            'work_type' => new WorkTypeResource($this->whenLoaded('workType')),
            'user' => new AuthorResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
