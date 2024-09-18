<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCoupon extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    public $timestamps = true;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['code', 'coupon_received', 'coupon_available', 'expired_at', 'user_id', 'banner_product_id'];

    protected $casts = [
        'code' => 'string',
        'expired_at' => 'datetime'
    ];

    protected $perPage = 30;

    static public $codePrefix = 3;

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

    public function bannerProduct(): BelongsTo
    {
        return $this->belongsTo(BannerProduct::class, 'banner_product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(UserCustomer::class, 'user_id');
    }

    public function pointTransactionLogs()
    {
        return $this->morphMany(PointTransactionLog::class, 'transactionable');
    }

    public function getCouponAvailableStatusFormat(): string
    {
        return $this->coupon_available <= 0 ? 'ใช้แล้ว' : 'ยังไม่ได้ใช้';
    }
}
