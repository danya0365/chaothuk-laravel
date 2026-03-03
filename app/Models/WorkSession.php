<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sessionable_type', 'sessionable_id', 'worker_id', 'customer_id',
        'bookingable_type', 'bookingable_id',
        'started_at', 'ended_at', 'total_duration_minutes',
        'price_agreed', 'status', 'notes', 'cancel_reason',
        'worker_confirm', 'customer_confirm',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'price_agreed' => 'decimal:2',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function sessionable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function bookingable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function locationLogs(): HasMany
    {
        return $this->hasMany(SessionLocationLog::class, 'session_id');
    }

    public function latestLocation()
    {
        return $this->hasOne(SessionLocationLog::class, 'session_id')->latestOfMany();
    }
}
