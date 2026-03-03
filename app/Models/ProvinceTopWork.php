<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Monthly top work per province.
 * Recalculated monthly via artisan command or scheduler.
 */
class ProvinceTopWork extends Model
{
    protected $fillable = [
        'province_id', 'work_id', 'author_id', 'period', 'rank',
        'booking_count', 'confirmed_count', 'review_count',
        'avg_rating', 'like_count', 'total_score',
    ];

    /**
     * Scope: current month's rankings
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where('period', now()->format('Y-m'));
    }

    /**
     * Scope: top 1 per province
     */
    public function scopeTopOnly($query)
    {
        return $query->where('rank', 1);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
