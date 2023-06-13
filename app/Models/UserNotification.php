<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UserNotification extends Model
{
    use SoftDeletes;

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
    protected $fillable = ['title','message','notification_type','review_id','author_id'];



}
