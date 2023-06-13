<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SubDistrict
 *
 * @property $id
 * @property $zip_code
 * @property $name_th
 * @property $name_en
 * @property $amphure_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class SubDistrict extends Model
{

    static $rules = [
        'zip_code' => 'required',
        'name_th' => 'required',
        'name_en' => 'required',
        'amphure_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['zip_code', 'name_th', 'name_en', 'amphure_id'];
}
