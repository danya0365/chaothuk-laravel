<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMerchant extends Model
{
    use HasFactory, Notifiable, Scopes;

    public $incrementing = false;

    public $primaryKey = 'user_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'name', 'image_url', 'desc', 'referral_program', 'address', 'province'];

    protected $casts = [
        'image_url' => 'string',
    ];

    protected $perPage = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function bannerProducts(): HasMany
    {
        return $this->hasMany(BannerProduct::class, 'merchant_id');
    }

    public function bannerPromotions(): HasMany
    {
        return $this->hasMany(BannerPromotion::class, 'merchant_id');
    }

    public function getLatestBannerProducts()
    {
        return $this->bannerProducts()->take(10)->orderBy('id', 'desc')->get();
    }

    public function getLatestBannerPromotions()
    {
        return $this->bannerPromotions()->take(10)->orderBy('id', 'desc')->get();
    }

    public function getMerchantName()
    {
        return $this->name;
    }

    public function referralProgram(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_program');
    }
}
