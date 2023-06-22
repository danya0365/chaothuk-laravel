<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UserNotification extends Model
{
  use SoftDeletes, Scopes;

  static $rules = [
    'notification_type' => 'required',
    'review_id' => 'required',
    'author_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['title', 'message', 'notification_type', 'review_id', 'is_read', 'author_id'];

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
