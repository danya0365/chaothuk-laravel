<?php

namespace App\Models;

use App\Enums\MissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMission extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['points', 'status', 'user_id', 'banner_promotion_id', 'note'];

    protected $perPage = 30;

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function bannerPromotion(): BelongsTo
    {
        return $this->belongsTo(BannerPromotion::class, 'banner_promotion_id');
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

    public function getMissionStatusFormat(): string
    {
        return __('mission.status-' . $this->status);
    }

    public function isComplete(): bool
    {
        return $this->status == MissionStatus::COMPLETE->value;
    }
}
