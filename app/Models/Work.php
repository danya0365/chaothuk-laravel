<?php

namespace App\Models;

use App\Traits\QueryTrait;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Work
 *
 * @property $id
 * @property $code
 * @property $title
 * @property $description
 * @property $details
 * @property $primary_image
 * @property $images
 * @property $price
 * @property $avg_review_rating
 * @property $display_priority
 * @property $work_status
 * @property $province_id
 * @property $work_type_id
 * @property $author_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Work extends Model
{
  use SoftDeletes, Scopes;

  static $rules = [
    'code' => 'required|unique:works',
    'title' => 'required',
    'description' => 'required',
    'province_id' => 'required',
    'work_type_id' => 'required',
    'author_id' => 'required',
    'price' => 'required',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'details' => 'array',
    'images' => 'array',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['code', 'title', 'description', 'details', 'primary_image', 'images', 'price', 'avg_review_rating', 'display_priority', 'work_status', 'province_id', 'work_type_id', 'author_id'];

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }

  public function province(): BelongsTo
  {
    return $this->belongsTo(Province::class, 'province_id');
  }

  public function workType(): BelongsTo
  {
    return $this->belongsTo(WorkType::class, 'work_type_id');
  }

  public function notifications()
  {
    return $this->morphMany(UserNotification::class, 'notificationable');
  }

  public function userLikes()
  {
    return $this->belongsToMany(User::class, 'work_likes', 'work_id', 'author_id');
  }

  public function bookings(): HasMany
  {
    return $this->hasMany(WorkBooking::class);
  }
}
