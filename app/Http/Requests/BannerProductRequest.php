<?php

namespace App\Http\Requests;

use App\Enums\CouponExpiresType;
use Illuminate\Foundation\Http\FormRequest;

class BannerProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'merchant_id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'condition_text' => ['required', 'string'],
            'tags' => ['required', 'string', 'max:255'],
            'image_url' => ['required', 'string', 'max:255'],
            'expired_at' => ['nullable', 'string'],
            'redeem_points' => ['required',  'numeric', 'between:0,10000'],
            'available_redeems' => ['required',  'numeric', 'between:-1,10000'],
            'max_redeem_per_user' => ['required',  'numeric', 'between:-1,10000'],
            'coupon_expires_type' => ['required', 'in:' . implode(',', CouponExpiresType::values())],
            'user_types' => ['required', 'array'],
        ];
    }
}
