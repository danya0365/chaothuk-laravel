<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The signed-in user's own profile.
 *
 * Permissions ride along because the mobile app gates its create buttons on them
 * (create_work / create_recruit) rather than hardcoding who can post what.
 *
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
            'location' => $this->location,
            'biography' => $this->biography,
            'profile_image' => image_url($this->profile_image),
            'cover_image' => image_url($this->cover_image),
            'permissions' => $this->resolvePermissions(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * Flatten the role + direct permission mix into one list of enabled slugs.
     *
     * Mirrors User::isPermission(): a permission granted directly on the user wins
     * over the role's, so a direct "false" has to drop the slug rather than just
     * skip adding it.
     *
     * @return array<int, string>
     */
    private function resolvePermissions(): array
    {
        $direct = [];

        foreach ($this->permissions as $permission) {
            $direct[$permission->slug] = (bool) $permission->pivot?->data;
        }

        $granted = [];

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->pivot?->data) {
                    $granted[$permission->slug] = true;
                }
            }
        }

        foreach ($direct as $slug => $allowed) {
            if ($allowed) {
                $granted[$slug] = true;
            } else {
                unset($granted[$slug]);
            }
        }

        return array_keys($granted);
    }
}
