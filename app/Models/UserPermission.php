<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPermission
 *
 * @property $id
 * @property $is_can_create_recruit
 * @property $is_can_create_work
 * @property $is_can_review_work
 * @property $is_can_reply_review
 * @property $is_can_access_supervisor
 * @property $is_can_access_admin
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class UserPermission extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_can_create_recruit' => 'boolean',
        'is_can_create_work' => 'boolean',
        'is_can_review_work' => 'boolean',
        'is_can_reply_review' => 'boolean',
        'is_can_access_supervisor' => 'boolean',
        'is_can_access_admin' => 'boolean',
    ];

    static $rules = [
        'is_can_create_recruit' => 'required',
        'is_can_create_work' => 'required',
        'is_can_review_work' => 'required',
        'is_can_reply_review' => 'required',
        'is_can_access_supervisor' => 'required',
        'is_can_access_admin' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['is_can_create_recruit', 'is_can_create_work', 'is_can_review_work', 'is_can_reply_review', 'is_can_access_supervisor', 'is_can_access_admin'];
}
