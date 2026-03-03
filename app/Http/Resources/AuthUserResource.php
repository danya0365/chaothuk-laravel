<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
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
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'location' => $this->location,
            'profile_image' => $this->profile_image,
            'cover_image' => $this->cover_image,
            'received_points' => $this->receivedPoints(),
            'available_points' => $this->availablePoints(),
            'redeem_points' => $this->redeemPoints(),
            'roles' => $this->roles,
            'permissions' => $this->permissions
        ];
    }
}
