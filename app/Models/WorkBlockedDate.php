<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkBlockedDate extends Model
{
    protected $fillable = [
        'work_id', 'blocked_date', 'reason',
    ];

    protected $casts = [
        'blocked_date' => 'date',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }
}
