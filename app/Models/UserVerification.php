<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVerification extends Model
{
    protected $fillable = ['user_id', 'verification_type', 'status', 'proof_data', 'verified_by', 'verified_at'];

    protected $casts = [
        'proof_data'  => 'array',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getLabelAttribute(): string
    {
        return match ($this->verification_type) {
            'phone'                => '📱 เบอร์โทร',
            'email'                => '📧 อีเมล',
            'id_card'              => '🪪 บัตรประชาชน',
            'driving_license'      => '🚗 ใบขับขี่',
            'vehicle_registration' => '🚛 ทะเบียนรถ',
            'criminal_record'      => '📋 ประวัติอาชญากรรม',
            'business_license'     => '🏢 ใบอนุญาตประกอบการ',
            default                => $this->verification_type,
        };
    }
}
