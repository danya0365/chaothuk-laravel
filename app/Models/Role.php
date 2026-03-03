<?php

namespace App\Models;

use App\Enums\Role as EnumsRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')->withPivot(['data', 'desc']);
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }

    public function permissionDetails(): string
    {
        $permissionRows = $this->permissions->toArray();
        if (count($permissionRows) == 0) {
            return "❌ ไม่พบสิทธิ";
        }
        $permissionDetails = array_map(function ($permission) {
            return  ($permission['pivot']['data'] ? "✅" : "❌") . " " . __('common.permission-' . $permission['slug']);
        }, $permissionRows);
        return implode(', ', $permissionDetails);
    }

    public function syncRolePermissions($rolePermissions)
    {
        $syncData = [];
        foreach ($rolePermissions as $rolePermission) {
            if (!isset($rolePermission['permission_id'])) {
                continue;
            }
            $random = substr(md5(mt_rand()), 0, 7);
            if (!isset($rolePermission['data'])) {
                $rolePermission['data'] = 1;
            }

            if (!$rolePermission['desc']) {
                $rolePermission['desc'] = '';
            }

            $rolePermission['data'] = $rolePermission['data'] == '1' || strtolower($rolePermission['data']) == 'yes' ? true : false;
            $syncData[$random] = $rolePermission;
        }
        if (count($syncData) === 0) {
            return $this->permissions()->sync([]);
        }
        return $this->permissions()->sync($syncData);
    }
}