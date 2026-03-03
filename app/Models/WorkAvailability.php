<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkAvailability extends Model
{
    protected $fillable = [
        'work_id', 'day_of_week', 'start_time', 'end_time', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get Thai day name
     */
    public function getDayNameAttribute(): string
    {
        return match ($this->day_of_week) {
            0 => 'อาทิตย์',
            1 => 'จันทร์',
            2 => 'อังคาร',
            3 => 'พุธ',
            4 => 'พฤหัสบดี',
            5 => 'ศุกร์',
            6 => 'เสาร์',
            default => '-',
        };
    }
}
