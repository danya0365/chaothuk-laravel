<?php

namespace App\Models;

use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Configuration extends Model
{
    use HasFactory, Notifiable, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['slug', 'name', 'value_type', 'value', 'value_options', 'group'];

    protected $casts = [
        'value_options' => 'json',
    ];

    static $rules = [
        'slug' => 'required',
        'name' => 'required',
        'value_type' => 'required',
        'value' => 'required',
    ];

    protected $perPage = 30;

    public function getCreateDate(): string
    {
        if (!$this->created_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        if (!$this->created_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function getUpdateDate(): string
    {
        if (!$this->updated_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->updated_at)->format('Y-m-d');
    }

    public function getUpdateDateFormat(): string
    {
        if (!$this->updated_at) return '';
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->updated_at)->format('d/m/Y');
    }

    public function getValue(): string
    {
        if (!$this->value) return $this->value;
        return mb_strlen($this->value) > 30 ? mb_substr($this->value, 0, 28) . '...' : $this->value;
    }
}
