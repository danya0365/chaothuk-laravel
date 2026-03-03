<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkResource extends JsonResource
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
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description,
            'details'       => array_filter(is_array($this->details) ? $this->details : (json_decode($this->details ?? '[]', true) ?? [])),
            'primary_image' => $this->primary_image,
            'images'        => array_filter(is_array($this->images) ? $this->images : (json_decode($this->images ?? '[]', true) ?? [])),
            'price' => $this->price,
            'like_count' => $this->like_count,
            'reply_count' => $this->reply_count,
            'avg_review_rating' => $this->avg_review_rating,
            'display_priority' => $this->display_priority,
            'work_status' => $this->work_status,
            'created_at' => $this->created_at,
            'author' => new UserResource($this->author),
            'province' => new ProvinceResource($this->province),
            'work_type' => new WorkTypeResource($this->workType),
            'categories' => $this->categories
        ];
    }
}