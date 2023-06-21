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
            'details' => array_filter($this->details ?? []),
            'primary_image' => $this->primary_image,
            'images' => array_filter($this->images ?? []),
            'price' => $this->price,
            'avg_review_rating' => $this->avg_review_rating,
            'display_priority' => $this->display_priority,
            'work_status' => $this->work_status,
            'created_at' => $this->created_at,
            'author' => $this->author,
            'province' => new ProvinceResource($this->province),
            'work_type' => new WorkTypeResource($this->workType)
        ];
    }
}