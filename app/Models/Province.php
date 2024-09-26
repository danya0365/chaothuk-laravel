<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Province
 *
 * @property $id
 * @property $code
 * @property $name_th
 * @property $name_en
 * @property $geography_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Province extends Model
{
    public static $rules = [
        'code' => 'required',
        'name_th' => 'required',
        'name_en' => 'required',
        'geography_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name_th', 'name_en', 'image', 'geography_id'];

    public function getImage($size = "480x480"): string
    {
        if (trim($this->image)) {
            return $this->image;
        }
        $text = urlencode($this->name_en);
        return $this->profile_image ?? "https://placehold.co/{$size}?text={$text}";
    }
}
