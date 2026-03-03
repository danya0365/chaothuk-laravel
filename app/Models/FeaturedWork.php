<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $id
 * @property $work_id
 * @property $author_id
 * @property $start_at
 * @property $end_at
 * @property $slot_position
 * @property $amount_paid
 * @property $payment_method
 * @property $point_transaction_id
 * @property $payment_status
 * @property $is_approved
 * @property $approved_by
 * @property $impression_count
 * @property $click_count
 */
class FeaturedWork extends Model
{
    use HasFactory, SoftDeletes, Scopes;

    protected $fillable = [
        'work_id', 'author_id', 'start_at', 'end_at', 'slot_position',
        'amount_paid', 'payment_method', 'point_transaction_id', 'payment_status',
        'is_approved', 'approved_by', 'impression_count', 'click_count',
    ];

    protected $casts = [
        'start_at'    => 'datetime',
        'end_at'      => 'datetime',
        'is_approved' => 'boolean',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Scope: only currently active featured items
     */
    public function scopeActive($query)
    {
        return $query->where('is_approved', true)
            ->where('payment_status', 'paid')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
