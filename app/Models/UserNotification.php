<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserNotification
 *
 * @property $id
 * @property $title
 * @property $message
 * @property $notification_type
 * @property $review_id
 * @property $author_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UserNotification extends Model
{
    use Scopes;
    use SoftDeletes;

    public static $rules = [
        'notification_type' => 'required',
        'author_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * The morph columns are included because every caller sets them from trusted
     * server-side code — leaving them out meant notifications were created with a
     * null notificationable_type, which the column does not allow.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'details',
        'notification_type',
        'is_read',
        'author_id',
        'notificationable_type',
        'notificationable_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
        'is_read' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function notificationable()
    {
        return $this->morphTo();
    }
}
