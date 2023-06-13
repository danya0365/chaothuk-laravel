<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
  use SoftDeletes;

  static $rules = [
    'code' => 'required',
    'title' => 'required',
    'description' => 'required',
    'details' => 'required',
    'province_id' => 'required',
    'work_type_id' => 'required',
    'author_id' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['code', 'title', 'description', 'details', 'primary_image', 'images', 'price', 'avg_review_rating', 'display_priority', 'work_status', 'province_id', 'work_type_id', 'author_id'];
}
