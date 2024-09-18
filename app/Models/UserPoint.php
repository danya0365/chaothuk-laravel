<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoint extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['point_received', 'point_available', 'expired_at', 'user_id', 'issue_point_id'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected $perPage = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issuePoint(): BelongsTo
    {
        return $this->belongsTo(IssuePoint::class, 'issue_point_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(UserCustomer::class, 'user_id');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }
}
