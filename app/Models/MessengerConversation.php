<?php

namespace App\Models;

use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessengerConversation extends Model
{
    use HasFactory, Notifiable, SoftDeletes, Scopes;

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['content', 'type', 'local_code_id', 'delivered_at', 'seen_at', 'user_id', 'channel_id'];

    static $rules = [
        'content' => 'required',
        'type' => 'required',
        'user_id' => 'required',
        'channel_id' => 'required',
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(MessengerChannel::class, 'channel_id');
    }
}
