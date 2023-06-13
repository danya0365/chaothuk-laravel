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

    static $rules = [
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
    protected $fillable = ['code', 'name_th', 'name_en', 'geography_id'];
}
