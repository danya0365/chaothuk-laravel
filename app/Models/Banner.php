<?php

namespace App\Models;

use App\Enums\BannerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'view_count', 'expired_at', 'type', 'image_url', 'external_url', 'is_public', 'banner_promotion_id', 'banner_product_id', 'is_pinned'];

    protected $perPage = 30;

    public function getExpiredDate(): string
    {
        if (!$this->expired_at) return 'ไม่ระบุ';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->expired_at)->format('Y-m-d');
    }

    public function getExpiredDateFormat(): string
    {
        if (!$this->expired_at) return 'ไม่ระบุ';
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

    public function getTypeFormat(): string
    {
        return __('banner.type-' . $this->type);
    }

    public function getIsPublicFormat(): string
    {
        return $this->is_public ? __("แสดง") : __("ไม่แสดง");
    }

    public function getIsPinnedFormat(): string
    {
        return $this->is_pinned ? __("แสดง") : __("ไม่แสดง");
    }

    public function isExternalUrl(): bool
    {
        return $this->type == BannerType::EXTERNAL_URL->value;
    }

    public function isProduct(): bool
    {
        return $this->type == BannerType::PRODUCT->value;
    }

    public function isPromotion(): bool
    {
        return $this->type == BannerType::PROMOTION->value;
    }

    public function bannerProduct(): BelongsTo
    {
        return $this->belongsTo(BannerProduct::class, 'banner_product_id');
    }

    public function bannerPromotion(): BelongsTo
    {
        return $this->belongsTo(BannerPromotion::class, 'banner_promotion_id');
    }
}