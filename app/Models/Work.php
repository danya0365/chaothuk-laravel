<?php

namespace App\Models;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use HasFactory, SoftDeletes, Scopes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
      'details' => 'array',
      'images' => 'array',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'title', 'description', 'details', 'primary_image', 'images', 'price', 'avg_review_rating', 'display_priority', 'latitude', 'longitude', 'work_status', 'province_id', 'work_type_id', 'author_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function activeFeature()
    {
        return $this->hasOne(FeaturedWork::class)
            ->where('is_approved', true)
            ->where('payment_status', 'paid')
            ->where('end_at', '>=', now());
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function workType(): BelongsTo
    {
        return $this->belongsTo(WorkType::class, 'work_type_id');
    }

    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notificationable');
    }

    public function userLikes()
    {
        return $this->belongsToMany(User::class, 'work_likes', 'work_id', 'author_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(WorkBooking::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'works_categories');
    }

    public function categoryIds(): array
    {
        $categoryIds = array_map(function ($category) {
            return  $category['id'];
        }, $this->categories?->toArray() ?? []);
        return $categoryIds;
    }

    public function categoryNames(): string
    {
        $roleNames = array_map(function ($category) {
            return  $category['name'];
        }, $this->categories?->toArray() ?? []);
        return implode(', ', $roleNames);
    }

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'works_reviews', 'work_id', 'post_id')->withTimestamps();
    }

    public function syncCategories($worksCategories)
    {
        $syncData = [];
        foreach ($worksCategories as $workCategory) {
            $random = substr(md5(mt_rand()), 0, 7);
            $syncData[$random] = $workCategory;
        }
        if (count($syncData) === 0) {
            return $this->categories()->sync([]);
        }
        return $this->categories()->sync($syncData);
    }
}
