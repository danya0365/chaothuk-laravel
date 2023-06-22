<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruitResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'primary_image' => $this->primary_image,
            'images' => array_filter($this->images ?? []),
            'budget' => $this->budget,
            'display_priority' => $this->display_priority,
            'recruit_status' => $this->recruit_status,
            'created_at' => $this->created_at,
            'author' => $this->author,
            'province' => new ProvinceResource($this->province),
            'work_type' => new WorkTypeResource($this->workType)
        ];
    }
}