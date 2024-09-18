<?php

namespace App\Models;

use App\Enums\Role as EnumsRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function isSupervisor(): bool
    {
        return $this->is_supervisor;
    }
}
