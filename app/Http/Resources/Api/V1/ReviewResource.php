<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A review — a Post the author left on a listing, with any replies threaded under
 * it.
 *
 * The web stores reviews and replies as one Post tree (parent_id links a reply),
 * and the ReviewSection livescreen walks it recursively, so the API returns the
 * same tree: `replies` nests ReviewResources as deep as the thread goes.
 *
 * @mixin \App\Models\Post
 */
class ReviewResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'rating' => $this->rating !== null ? (int) $this->rating : null,
            'images' => collect($this->images ?? [])
                ->map(fn ($path) => image_url($path))
                ->values(),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'replies' => self::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
