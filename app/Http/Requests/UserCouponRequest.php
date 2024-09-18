<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'numeric'],
            'coupon_received' => ['required', 'numeric', 'between:0,1'],
            'coupon_available' => ['required', 'numeric', 'between:0,1'],
            'expired_at' => ['required', 'string'],
            'banner_product_id' => ['required', 'numeric'],
        ];
    }
}
