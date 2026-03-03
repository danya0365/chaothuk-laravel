<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReputationLog extends Model
{
    protected $fillable = ['user_id', 'event_type', 'score_before', 'score_after', 'metadata'];

    protected $casts = [
        'metadata'     => 'array',
        'score_before' => 'float',
        'score_after'  => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
