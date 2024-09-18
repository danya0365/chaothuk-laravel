<?php

namespace App\Models;

use App\Enums\CouponExpiresType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\BelongsToManyRelationship;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerProduct extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['code', 'name', 'detail', 'condition_text', 'tags', 'image_url', 'expired_at', 'redeem_points', 'available_redeems', 'max_redeem_per_user', 'coupon_expires_type', 'merchant_id'];

    protected $casts = [
        'tags' => 'json',
        'expired_at' => 'date'
    ];

    static public $codePrefix = 1;

    protected $perPage = 30;

    public function getExpiredDate(): string
    {
        if (!$this->expired_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->expired_at)->format('Y-m-d');
    }

    public function getExpiredDateFormat(): string
    {
        if (!$this->expired_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->expired_at)->format('d/m/Y');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function isExpired(): bool
    {
        return !$this->expired_at->isFuture();
    }

    public function calculateCouponExpireAt()
    {
        switch ($this->coupon_expires_type) {
            case CouponExpiresType::TYPE_1->value:
                return Carbon::now()->addDays(30);
            case CouponExpiresType::TYPE_2->value:
                return Carbon::now()->addDays(30 * 6);
            case CouponExpiresType::TYPE_3->value:
                return Carbon::now()->addDays(30 * 12);
            default:
                return Carbon::now()->addDays(30);
        }
    }

    public function isRedeemAvailable(): bool
    {
        return $this->available_redeems == -1 || $this->available_redeems > 0;
    }

    public function textTags(): string
    {
        if ($this->tags && is_array($this->tags)) {
            $trimTags = array_map('trim', $this->tags);
            return implode(', ', $trimTags);
        }
        return '';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(UserMerchant::class, 'merchant_id');
    }

    public function userTypes(): BelongsToMany
    {
        return $this->belongsToMany(UserType::class, 'banner_product_user_types');
    }

    public function getMerchantName(): string
    {
        $merchant = $this->user->merchant;
        if ($merchant) {
            return $merchant->name;
        }
        return $this->user->name;
    }

    public function getMerchantId(): string
    {
        $merchant = $this->user->merchant;
        if ($merchant) {
            return $merchant->user_id;
        }
        return $this->user->id;
    }

    public function getCouponExpiresTypeFormat(): string
    {
        return __('banner.coupon_expires_type-' . $this->coupon_expires_type);
    }

    public function getMaxRedeemPerUserFormat(): string
    {
        if ($this->max_redeem_per_user == -1) return 'ไม่จำกัด';
        return number_format($this->max_redeem_per_user);
    }

    public function getAvailableRedeems(): string
    {
        if ($this->available_redeems == -1) return 'ไม่จำกัด';
        return number_format($this->available_redeems);
    }

    public function getRedeemPoints(): string
    {
        return number_format($this->redeem_points);
    }

    public function getUserTypesValue(): array
    {
        $userTypes = $this->userTypes;
        if (!$userTypes) return [];
        $userTypeIds = [];
        foreach ($userTypes as $userType) {
            $userTypeIds[] = $userType->id;
        }
        return $userTypeIds;
    }

    public function getUserTypesFormat(): string
    {
        $userTypes = $this->userTypes;
        if (!$userTypes) return 'ไม่มี';
        $userTypeNames = [];
        foreach ($userTypes as $userType) {
            $userTypeNames[] = $userType->name;
        }
        if (count($userTypeNames) === 0) return 'ไม่มี';
        return implode(', ', $userTypeNames);
    }

    public function syncUserType($userTypes)
    {
        return $this->userTypes()->sync($userTypes);
    }
}
