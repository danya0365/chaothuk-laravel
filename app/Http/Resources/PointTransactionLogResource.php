<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointTransactionLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transactionable = get_class($this->transactionable);
        return [
            'id'   => $this->id,
            'user' => $this->user,
            'type' => $transactionable,
            'transactionable' => $this->transactionable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
