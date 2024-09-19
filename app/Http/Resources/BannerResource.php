<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'view_count' => $this->view_count,
            'type' => $this->type,
            'image_url' => $this->image_url,
            'external_url' => $this->external_url,
            'is_public' => $this->is_public,
            'is_pinned' => $this->is_pinned,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
