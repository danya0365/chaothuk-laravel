<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One card of the home-screen banner strip — the fields the strip on the
 * web's front page shows: the picture, the label and where a tap goes.
 *
 * @mixin \App\Models\Banner
 */
class BannerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => image_url($this->image_url),
            'external_url' => $this->external_url,
            'is_pinned' => (bool) $this->is_pinned,
        ];
    }
}
