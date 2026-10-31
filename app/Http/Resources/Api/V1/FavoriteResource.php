<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Recruit;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One saved bookmark of the caller.
 *
 * `favoritable` is a morph — a work on one card, a recruit on another — so the
 * payload uses the same shape the marketplace already renders, and `type` is the
 * short class name the client routes with.
 *
 * @mixin \App\Models\Favorite
 */
class FavoriteResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->favoritableType(),
            'favoritable' => $this->favoritableResource(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * "App\Models\Work" → "work", matching the wire keys the bookmarks are
     * created with.
     */
    private function favoritableType(): string
    {
        $class = str_replace('App\\Models\\', '', $this->favoritable_type);

        return strtolower($class);
    }

    /**
     * A missing favoritable means the listing was deleted while bookmarked —
     * the client drops the row instead of rendering a broken card.
     */
    private function favoritableResource(): ?JsonResource
    {
        if (! $this->relationLoaded('favoritable') || $this->favoritable === null) {
            return null;
        }

        return match ($this->favoritable_type) {
            Work::class => new WorkResource($this->favoritable),
            Recruit::class => new RecruitResource($this->favoritable),
            default => null,
        };
    }
}
