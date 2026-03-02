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
    use HasFactory, SoftDeletes, Scopes;

    public static $rules = [
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
      'details' => 'array',
      'images' => 'array',
    ];


    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'details', 'primary_image', 'images', 'budget', 'display_priority', 'recruit_status', 'province_id', 'work_type_id', 'author_id'];

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

    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notificationable');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RecruitBooking::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'recruits_categories');
    }

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'recruits_reviews', 'recruit_id', 'post_id')->withTimestamps();
    }
}
