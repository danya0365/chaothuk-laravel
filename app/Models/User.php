<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

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
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Scopes;

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

    public function getRoleName(): string
    {
        return __('common.role-' . $this->role_id);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(UserCustomer::class, 'user_id');
    }

    public function merchant(): HasOne
    {
        return $this->hasOne(UserMerchant::class, 'user_id');
    }

    public function backend(): HasOne
    {
        return $this->hasOne(UserBackend::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function userTypes(): BelongsToMany
    {
        return $this->belongsToMany(UserType::class, 'user_type_maps');
    }

    public function userTypeMaps(): HasMany
    {
        return $this->hasMany(UserTypeMap::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class, 'user_id');
    }

    public function missions(): HasMany
    {
        return $this->hasMany(UserMission::class, 'user_id');
    }

    public function userPoints(): HasMany
    {
        return $this->hasMany(UserPoint::class, 'user_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(BannerPromotion::class, 'merchant_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(BannerProduct::class, 'merchant_id');
    }

    public function transactions()
    {
        return $this->hasMany(PointTransactionLog::class, 'user_id');
    }

    public function promotionCount()
    {
        $promotionCount = $this->promotions()->count();
        if (!$promotionCount) {
            return 0;
        }
        return $promotionCount;
    }

    public function productCount()
    {
        $productCount = $this->products()->count();
        if (!$productCount) {
            return 0;
        }
        return $productCount;
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

    public function isCanAccessBackend(): Bool
    {
        return $this->role->is_can_access_backend;
    }

    public static function getOrCreateTelephoneUser($telephone): User
    {
        $user = self::where('name', $telephone)->first();
        if ($user) return $user;

        $user = User::create([
            'name' => $telephone,
            'email' => $telephone,
            'password' => Hash::make($telephone),
            'role_id' => RoleEnum::CUSTOMER->value,
        ]);

        event(new Registered($user));

        return $user;
    }

    public function getUserTypeValue(): array
    {
        $userTypeMaps = $this->userTypeMaps;
        if (!$userTypeMaps) return [];
        $userTypeIds = [];
        foreach ($userTypeMaps as $userType) {
            $userTypeIds[] = $userType->user_type_id;
        }
        return $userTypeIds;
    }

    public function syncUserTypes($userTypes)
    {
        $syncData = [];
        foreach ($userTypes as $userType) {
            if (!isset($userType['user_type_id'])) continue;
            $random = substr(md5(mt_rand()), 0, 7);
            if (!$userType['amount']) {
                $userType['amount'] = 0;
            }
            if (!$userType['text_condition']) {
                $userType['text_condition'] = '-';
            }
            $userType['amount'] = intval($userType['amount']);
            $syncData[$random] = $userType;
        }
        if (count($syncData) === 0) return null;
        return $this->userTypes()->sync($syncData);
    }
}
