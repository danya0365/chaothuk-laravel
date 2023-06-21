<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'profile_image', 'cover_image', 'first_name', 'last_name', 'birth_date', 'mobile_phone', 'location', 'biography'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $perPage = 20;

    static $rules = [
        'name' => 'required',
        'email' => 'required',
    ];

    public function permission(): HasOne
    {
        return $this->hasOne(UserPermission::class, 'user_id');
    }

    public function isCanAccessSupervisor(): Bool
    {
        return $this->permission()->get()->first()->is_can_access_supervisor;
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAvatar($size = 64): string
    {
        $fullName = trim($this->getFullName());
        $name = $fullName ? $fullName : $this->name;
        return $this->profile_image ?? "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff&size={$size}";
    }

    public function getCoverImage($size = "1200x600"): string
    {
        return $this->profile_image ?? "https://placehold.co/{$size}?text=Cover+Photo";
    }

    public function likedWorks()
    {
        return $this->belongsToMany(Work::class, 'work_likes', 'author_id', 'work_id')->withTimestamps();
    }

    public function bookedWorks()
    {
        return $this->belongsToMany(Work::class, 'work_bookings', 'author_id', 'work_id')->withTimestamps();
    }

    public function works()
    {
        return $this->hasMany(Work::class, 'author_id');
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class, 'author_id');
    }

    public function recruits()
    {
        return $this->hasMany(Recruit::class, 'author_id');
    }
}