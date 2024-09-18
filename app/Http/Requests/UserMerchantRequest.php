<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserMerchantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'numeric'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', Rule::unique('users', 'email')->ignore($this->user_merchant?->user_id, 'id')],
            'password' => ['nullable', 'string'],

            'profile_image' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],

            'shop_name' => ['required', 'min:2', 'max:30', 'nullable'],
            'image_url' => ['required', 'string', 'max:255'],
            'desc' => ['required', 'string'],
            'address' => ['required', 'string'],
            'province' => ['required', 'string', 'max:255'],
            'referral_program' => ['nullable', 'numeric', 'exists:App\Models\User,id'],
        ];
    }
}
