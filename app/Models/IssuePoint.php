<?php

namespace App\Models;

use App\Enums\CronRepeatType;
use App\Enums\IssueStatus;
use App\Enums\IssueType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuePoint extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['slug', 'name', 'desc', 'points', 'type', 'status', 'user_id', 'user_mission_id', 'cron_task', 'cron_info', 'start_at', 'end_at'];

    protected $casts = [
        'cron_info' => 'json',
    ];

    protected $perPage = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(UserCustomer::class, 'user_id');
    }

    public function userMission(): BelongsTo
    {
        return $this->belongsTo(UserMission::class, 'user_mission_id');
    }

    public function pointTransactionLogs()
    {
        return $this->morphMany(PointTransactionLog::class, 'transactionable');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function getStartDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->start_at)->format('Y-m-d');
    }

    public function getStartDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->start_at)->format('d/m/Y');
    }

    public function getEndDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->end_at)->format('Y-m-d');
    }

    public function getEndDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->end_at)->format('d/m/Y');
    }

    public function getStatusFormat(): string
    {
        return __('issue.status-' . $this->status);
    }

    public function getTypeFormat(): string
    {
        return __('issue.type-' . $this->type);
    }

    public function isTypeRepeat(): string
    {
        return $this->type == IssueType::REPEAT->value;
    }

    public function isApproved(): bool
    {
        return $this->status == IssueStatus::APPROVE->value;
    }

    public function cron(): CronInfo
    {
        $cronInfo =  CronInfo::createFromJson(($this->cron_info));
        return $cronInfo;
    }
}

class CronInfo
{
    public string $repeatType;
    public string $repeatValue;

    public static function createFromJson($object)
    {
        $cronInfo = new CronInfo();
        $cronInfo->repeatType = $object['repeat_type'] ?? '';
        $cronInfo->repeatValue = $object['repeat_value'] ?? '';
        return $cronInfo;
    }

    public function getRepeatType(): string
    {
        return $this->repeatType;
    }

    public function getRepeatValue(): string
    {
        return $this->repeatValue;
    }

    public function getRepeatWeekDayFormat(): string
    {
        return __('common.weekday-' . strtolower($this->repeatValue));
    }

    public function isTypeWeekDayInWeek(): bool
    {
        return $this->repeatType == CronRepeatType::AT_WEEKDAY_IN_WEEK->value;
    }

    public function isTypeDateInMonth(): bool
    {
        return $this->repeatType == CronRepeatType::AT_DATE_IN_MONTH->value;
    }
}
