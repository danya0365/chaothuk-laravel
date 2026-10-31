<?php

namespace App\Models;

use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class MessengerChannel extends Model
{
    use HasFactory, Notifiable, Scopes, SoftDeletes;

    /**
     * The attributes that should be mass-assignable.
     *
     * The booking ids are included because they are set from server-side code
     * when a booking opens its room; leaving them out made the column silently
     * stay null even on create.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'is_direct',
        'is_public',
        'total_participants',
        'work_booking_id',
        'recruit_booking_id',
    ];

    public static $rules = [
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
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MessengerParticipant::class, 'channel_id');
    }

    /**
     * The booking this room was opened for, on either side of the marketplace.
     *
     * Nullable because seeded and manually created channels belong to none.
     */
    public function workBooking(): BelongsTo
    {
        return $this->belongsTo(WorkBooking::class, 'work_booking_id');
    }

    public function recruitBooking(): BelongsTo
    {
        return $this->belongsTo(RecruitBooking::class, 'recruit_booking_id');
    }

    /**
     * True when this room came from a booking rather than being created by hand.
     */
    public function getBookingIdAttribute(): ?int
    {
        return $this->work_booking_id ?? $this->recruit_booking_id;
    }

    /**
     * Which list endpoint the room belongs to, so the client can refresh the
     * booking it points at without inspecting ids.
     */
    public function getBookingTypeAttribute(): ?string
    {
        if ($this->work_booking_id !== null) {
            return 'work-booking';
        }

        if ($this->recruit_booking_id !== null) {
            return 'recruit-booking';
        }

        return null;
    }

    /**
     * The name the caller should see for this room: the other participant's.
     *
     * Falls back to the stored title for a channel the caller is not a
     * participant of (which the API rejects anyway) and for group rooms.
     */
    public function counterpartName(?int $userId): string
    {
        $other = $this->participants->firstWhere('user_id', '!=', $userId);

        return $other?->author?->getName() ?? $this->title;
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(MessengerConversation::class, 'channel_id');
    }

    public function latestConversations(): HasMany
    {
        return $this->hasMany(MessengerConversation::class, 'channel_id')->orderBy('id', 'desc')->limit(1);
    }

    public function getAdmin(): ?MessengerParticipant
    {
        if (count($this->participants) > 0) {
            $adminParticipant = null;
            foreach ($this->participants as $participant) {
                if (! $participant->is_customer) {
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

    public function getCustomer(): ?MessengerParticipant
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

    public function getCustomerAvatar(): ?string
    {
        $customer = $this->getCustomer();

        return $customer ? $customer->author->getAvatar() : null;
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

    public static function getAdminAvatar(): ?string
    {
        return null;
    }

    public function getAdminLastSeen(): string
    {
        $admin = $this->getAdmin();

        return $admin ? $admin->last_seen_at : '-';
    }
}
