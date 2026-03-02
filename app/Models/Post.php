<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Post
 *
 * @property $id
 * @property $title
 * @property $content
 * @property $images
 * @property $rating
 * @property $author_id
 * @property $parent_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Post extends Model
{
    use SoftDeletes;
    use Scopes;

    protected $perPage = 20;

    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = ['title', 'content', 'images', 'rating', 'author_id', 'parent_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function userLikes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes', 'post_id', 'author_id')->withTimestamps();
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'works_reviews', 'post_id', 'work_id')->withTimestamps();
    }

    public function recruits(): BelongsToMany
    {
        return $this->belongsToMany(Recruit::class, 'recruits_reviews', 'post_id', 'recruit_id')->withTimestamps();
    }
}
