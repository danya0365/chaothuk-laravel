<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReputationReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reviewer_id', 'reviewee_id', 'booking_id', 'booking_type',
        'quality_rating', 'timeliness_rating', 'communication_rating',
        'professionalism_rating', 'overall_rating', 'comment', 'response',
        'is_verified_booking',
    ];

    protected $casts = [
        'is_verified_booking' => 'boolean',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
