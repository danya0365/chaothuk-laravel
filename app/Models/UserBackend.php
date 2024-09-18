<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBackend extends Model
{
    use HasFactory, Notifiable, Scopes;

    public $incrementing = false;

    public $primaryKey = 'user_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'is_can_approved'];

    protected $casts = [
        'is_can_approved' => 'boolean',
    ];

    protected $perPage = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function getIsCanApprovedFormat(): string
    {
        return $this->is_can_approved ? __("common.yes") : __("common.no");
    }
}
