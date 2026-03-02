<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'images'     => array_filter($this->images ?? []),
            'rating'     => $this->rating,
            'like_count' => $this->userLikes ? $this->userLikes->count() : 0,
            'created_at' => $this->created_at,
            'author'     => new UserResource($this->author),
            'replies'    => PostResource::collection($this->whenLoaded('replies')),
        ];
    }
}
