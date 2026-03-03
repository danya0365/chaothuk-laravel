<?php

namespace App\Models;

use App\Enums\TrustLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReputation extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id', 'overall_score', 'quality_score', 'timeliness_score',
        'communication_score', 'professionalism_score', 'total_reviews',
        'total_completed_jobs', 'total_cancelled_jobs', 'completion_rate',
        'response_rate', 'avg_response_minutes', 'repeat_customer_count',
        'trust_level', 'total_points_earned',
    ];

    protected $casts = [
        'overall_score'       => 'float',
        'quality_score'       => 'float',
        'timeliness_score'    => 'float',
        'communication_score' => 'float',
        'professionalism_score' => 'float',
        'completion_rate'     => 'float',
        'response_rate'       => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTrustLevelLabelAttribute(): string
    {
        return match ($this->trust_level) {
            'new'      => '🆕 ใหม่',
            'bronze'   => '🥉 บรอนซ์',
            'silver'   => '🥈 ซิลเวอร์',
            'gold'     => '🥇 โกลด์',
            'platinum' => '💎 แพลตินัม',
            'diamond'  => '👑 ไดมอนด์',
            default    => $this->trust_level,
        };
    }
}
