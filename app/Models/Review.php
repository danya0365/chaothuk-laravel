<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Review
 *
 * @property $id
 * @property $title
 * @property $message
 * @property $rating
 * @property $author_id
 * @property $work_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Review extends Model
{
    use SoftDeletes;

    static $rules = [
		'rating' => 'required',
		'author_id' => 'required',
		'work_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title','message','rating','author_id','work_id'];



}
