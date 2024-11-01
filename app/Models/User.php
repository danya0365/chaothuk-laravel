<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'profile_image', 'cover_image', 'first_name', 'last_name', 'birth_date', 'mobile_phone', 'location', 'biography', 'theme', 'role_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $perPage = 30;

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAvatar($size = 64): string
    {
        $fullName = trim($this->getFullName());
        $name = $fullName ? $fullName : $this->name;
        return $this->profile_image ?? "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff&size={$size}";
    }

    public function getCoverImage($size = "1200x600"): string
    {
        return $this->cover_image ?? "https://placehold.co/{$size}?text=Cover+Photo";
    }

    public function userPoints(): HasMany
    {
        return $this->hasMany(UserPoint::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(PointTransactionLog::class, 'user_id');
    }

    public function receivedPoints()
    {
        $receivedPoints = $this->userPoints()->sum('point_received');
        if (!$receivedPoints) {
            return 0;
        }
        return $receivedPoints;
    }

    public function availablePoints()
    {
        $pointAvailable = $this->userPoints()->where(function ($q) {
            return $q->whereNull('expired_at')->orWhere('expired_at', '>=', Carbon::now());
        })->sum('point_available');
        if (!$pointAvailable) {
            return 0;
        }
        return $pointAvailable;
    }

    public function redeemPoints()
    {
        $redeemPoints = $this->userPoints()->sum(DB::raw('point_received-point_available'));
        if (!$redeemPoints) {
            return 0;
        }
        return $redeemPoints;
    }

    public static function getOrCreateMobilePhoneUser($mobilePhone): User
    {
        $user = self::where('name', $mobilePhone)->first();
        if ($user) {
            return $user;
        }

        $user = User::create([
            'name' => $mobilePhone,
            'email' => $mobilePhone,
            'password' => Hash::make($mobilePhone),
        ]);

        $user->roles()->sync(['role_id' => RoleEnum::MOBILE_PHONE->value]);

        event(new Registered($user));

        return $user;
    }

    public function getRolesValue(): array
    {
        $usersRoles = $this->usersRoles;
        if (!$usersRoles) {
            return [];
        }
        $roleIds = [];
        foreach ($usersRoles as $usersRole) {
            $roleIds[] = $usersRole->role_id;
        }
        return $roleIds;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')->withPivot(['data', 'desc']);
    }

    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function isPermission($slug): bool
    {
        $permissions = $this->permissions;
        foreach ($permissions as $permission) {
            if ($permission->slug == $slug) {
                return $permission->pivot->data;
            }
        }

        $roles = $this->roles;
        foreach ($roles as $role) {
            $permissions = $role->permissions;
            foreach ($permissions as $permission) {
                if ($permission->slug == $slug) {
                    return $permission->pivot->data;
                }
            }
        }
        return false;
    }

    public function isCanAccessBackend(): bool
    {
        return $this->isPermission(PermissionEnum::ACCESS_BACKEND->value);
    }

    public function isCanManageRole(): bool
    {
        return $this->isPermission(PermissionEnum::MANAGE_ROLE->value);
    }

    public function isCanManagePermission(): bool
    {
        return $this->isPermission(PermissionEnum::MANAGE_PERMISSION->value);
    }

    public function roleNames(): string
    {
        $roleNames = array_map(function ($role) {
            return  __('common.role-' . $role['id']);
        }, $this->roles->toArray());
        return implode(', ', $roleNames);
    }

    public function roleIds(): array
    {
        $roleIds = array_map(function ($role) {
            return  $role['id'];
        }, $this->roles->toArray());
        return $roleIds;
    }

    public function syncUserPermissions($userPermissions)
    {
        $syncData = [];
        foreach ($userPermissions as $userPermission) {
            if (!isset($userPermission['permission_id'])) {
                continue;
            }
            $random = substr(md5(mt_rand()), 0, 7);
            if (!isset($userPermission['data'])) {
                $userPermission['data'] = 1;
            }

            if (!$userPermission['desc']) {
                $userPermission['desc'] = '';
            }

            $userPermission['data'] = $userPermission['data'] == '1' || strtolower($userPermission['data']) == 'yes' ? true : false;
            $syncData[$random] = $userPermission;
        }
        if (count($syncData) === 0) {
            return $this->permissions()->sync([]);
        }
        return $this->permissions()->sync($syncData);
    }

    public function syncUserRoles($userRoles)
    {
        $syncData = [];
        foreach ($userRoles as $userRole) {
            $random = substr(md5(mt_rand()), 0, 7);
            $syncData[$random] = $userRole;
        }
        if (count($syncData) === 0) {
            return $this->roles()->sync([]);
        }
        return $this->roles()->sync($syncData);
    }

    public function permissionDetails(): string
    {
        $permissionRows = $this->permissions->toArray();
        if (count($permissionRows) == 0) {
            return "❌ ไม่พบสิทธิ";
        }
        $permissionDetails = array_map(function ($permission) {
            return ($permission['pivot']['data'] ? "✅" : "❌") . " " .  __('common.permission-' . $permission['slug']);
        }, $permissionRows);
        return implode(', ', $permissionDetails);
    }

    public function likedWorks()
    {
        return $this->belongsToMany(Work::class, 'work_likes', 'author_id', 'work_id')->withTimestamps();
    }

    public function bookedWorks()
    {
        return $this->belongsToMany(Work::class, 'work_bookings', 'author_id', 'work_id')->withTimestamps();
    }

    public function works()
    {
        return $this->hasMany(Work::class, 'author_id');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'author_id');
    }

    public function recruits()
    {
        return $this->hasMany(Recruit::class, 'author_id');
    }

    public function bookedRecruits()
    {
        return $this->belongsToMany(Recruit::class, 'recruit_bookings', 'author_id', 'recruit_id')->withTimestamps();
    }

}
