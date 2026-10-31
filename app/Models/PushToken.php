<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One registered device for Expo push delivery.
 *
 * A user may hold several devices, so the relation is one-to-many; the token
 * itself is unique because a device that re-registering must update its row
 * rather than pile up duplicates.
 */
class PushToken extends Model
{
    protected $fillable = ['user_id', 'token', 'platform'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
