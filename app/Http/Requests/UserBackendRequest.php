<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserBackendRequest extends FormRequest
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
            'email' => ['required', 'string', Rule::unique('users', 'email')->ignore($this->user_backend?->user_id, 'id')],
            'password' => ['nullable', 'string'],

            'profile_image' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],

            'is_can_approved' => ['required', 'boolean'],
        ];
    }
}
