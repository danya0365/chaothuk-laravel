<?php

namespace App\Models;

use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessengerChannel extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['slug', 'title', 'is_direct', 'is_public', 'total_participants'];

    static $rules = [
        'slug' => 'required',
        'title' => 'required',
    ];

    protected $casts = [
        'is_direct' => 'boolean',
        'is_public' => 'boolean',
    ];

    protected $perPage = 30;

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MessengerParticipant::class, 'channel_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(MessengerConversation::class, 'channel_id');
    }

    public function latestConversations(): HasMany
    {
        return $this->hasMany(MessengerConversation::class, 'channel_id')->orderBy('id', 'desc')->limit(1);
    }

    public function getAdmin(): MessengerParticipant | null
    {
        if (count($this->participants) > 0) {
            $adminParticipant = null;
            foreach ($this->participants as $participant) {
                if (!$participant->is_customer) {
                    $adminParticipant = $participant;
                    break;
                }
            }

            if ($adminParticipant) {
                return $adminParticipant;
            }
        }
        return null;
    }

    public function getCustomer(): MessengerParticipant | null
    {
        if (count($this->participants) > 0) {
            $customerParticipant = null;
            foreach ($this->participants as $participant) {
                if ($participant->is_customer) {
                    $customerParticipant = $participant;
                    break;
                }
            }

            if ($customerParticipant) {
                return $customerParticipant;
            }
        }
        return null;
    }

    public function getCustomerName(): string
    {
        $customer = $this->getCustomer();
        return $customer ? $customer->author->getName() : __('No Customer');
    }

    public function getCustomerAvatar(): string
    {
        $customer = $this->getCustomer();
        return $customer ? $customer->author->getAvatar() : 'https://ui-avatars.com/api/?name=NO&background=0D8ABC&color=fff&size=200';
    }

    public function getCustomerLastSeen(): string
    {
        $customer = $this->getCustomer();
        return $customer ? ($customer->last_seen_at ?? '') : '';
    }

    public static function getAdminName(): string
    {
        return __('Admin');
    }

    public static function getAdminAvatar(): string
    {
        return 'https://ui-avatars.com/api/?name=AD&background=0D8ABC&color=fff&size=200';
    }

    public function getAdminLastSeen(): string
    {
        $admin = $this->getAdmin();
        return $admin ? $admin->last_seen_at : '-';
    }
}
