<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProvinceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'list' => $this->collection->map(function ($province) {
                return [
                    'id'   => $province->id,
                    'title' => $province->name_th,
                    'photo' => $province->getImage()
                ];
            })
        ];
    }
}
