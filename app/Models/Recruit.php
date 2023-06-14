<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Recruit
 *
 * @property $id
 * @property $title
 * @property $description
 * @property $primary_image
 * @property $images
 * @property $budget
 * @property $display_priority
 * @property $recruit_status
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
class Recruit extends Model
{
  use SoftDeletes;

  static $rules = [
    'title' => 'required',
    'description' => 'required',
    'province_id' => 'required',
    'work_type_id' => 'required',
    'author_id' => 'required',
    'budget' => 'required',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'images' => 'array',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['title', 'description', 'primary_image', 'images', 'budget', 'display_priority', 'recruit_status', 'province_id', 'work_type_id', 'author_id'];

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
}
