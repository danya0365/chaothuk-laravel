<?php

namespace App\Models;

use App\Enums\BadgeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    protected $fillable = ['user_id', 'badge_type', 'badge_level', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLabelAttribute(): string
    {
        return match ($this->badge_type) {
            'fast_responder' => '⚡ ตอบเร็ว',
            'on_time_king'   => '⏰ ตรงเวลา',
            'five_star'      => '⭐ 5 ดาว',
            'top_earner'     => '💰 งานเยอะ',
            'verified_pro'   => '✅ ยืนยันแล้ว',
            'repeat_magnet'  => '🔁 ลูกค้าประจำ',
            'zero_cancel'    => '🎯 ไม่เคยยกเลิก',
            'community_hero' => '🦸 ฮีโร่ชุมชน',
            default          => $this->badge_type,
        };
    }
}
