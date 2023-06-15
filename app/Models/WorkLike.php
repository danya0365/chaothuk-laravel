<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkLike
 *
 * @property $id
 * @property $work_id
 * @property $author_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class WorkLike extends Model
{
  use SoftDeletes, Scopes;

  static $rules = [
    'work_id' => 'required',
    'author_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['work_id', 'author_id'];

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'work_id');
  }

  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
