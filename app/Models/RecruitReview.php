<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RecruitReview
 * Pivot model linking Post reviews to Recruits
 *
 * @property $id
 * @property $recruit_id
 * @property $post_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RecruitReview extends Model
{
    use SoftDeletes;

    protected $table = 'recruits_reviews';

    protected $fillable = ['recruit_id', 'post_id'];

    public function recruit(): BelongsTo
    {
        return $this->belongsTo(Recruit::class, 'recruit_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
