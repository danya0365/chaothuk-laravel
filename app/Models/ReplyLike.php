<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReplyLike
 *
 * @property $id
 * @property $reply_id
 * @property $author_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ReplyLike extends Model
{
    use SoftDeletes;

    static $rules = [
		'reply_id' => 'required',
		'author_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['reply_id','author_id'];



}
