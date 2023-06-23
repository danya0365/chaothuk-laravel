<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkBooking
 *
 * @property $id
 * @property $customer_message
 * @property $mobile_phone
 * @property $latitude
 * @property $longitude
 * @property $booking_status
 * @property $customer_confirm_status
 * @property $worker_confirm_status
 * @property $author_id
 * @property $work_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WorkBooking extends Model
{
  use SoftDeletes, Scopes;

  static $rules = [
    'booking_date' => 'required',
    'booking_status' => 'required',
    'customer_confirm_status' => 'required',
    'worker_confirm_status' => 'required',
    'author_id' => 'required',
    'work_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['customer_message', 'mobile_phone', 'latitude', 'longitude', 'booking_status', 'customer_confirm_status', 'worker_confirm_status', 'author_id', 'work_id'];

  public function notifications()
  {
    return $this->morphMany(UserNotification::class, 'notificationable');
  }

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'work_id');
  }

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id');
  }
}
