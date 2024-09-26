<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class District
 *
 * @property $id
 * @property $code
 * @property $name_th
 * @property $name_en
 * @property $province_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class District extends Model
{

    static $rules = [
        'code' => 'required',
        'name_th' => 'required',
        'name_en' => 'required',
        'province_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name_th', 'name_en', 'province_id'];
}
