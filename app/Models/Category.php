<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Category
 *
 * @property $id
 * @property $title
 * @property $description
 * @property $image_url
 * @property $created_at
 * @property $updated_at
 *
 * @property RecruitsCategory[] $recruitsCategories
 * @property WorksCategory[] $worksCategories
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model
{
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'image_url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recruits(): BelongsToMany
    {
        return $this->belongsToMany(Recruit::class, 'recruits_categories');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'works_categories');
    }
}
