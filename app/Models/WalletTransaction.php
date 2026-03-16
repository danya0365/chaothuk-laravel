<?php

namespace App\Models;

use App\Enums\WalletTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_wallet_id',
        'type',
        'amount',
        'balance_after',
        'reference_id',
        'reference_type',
        'description',
    ];

    protected $casts = [
        'type' => WalletTransactionType::class,
    ];

    public function wallet()
    {
        return $this->belongsTo(UserWallet::class, 'user_wallet_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
