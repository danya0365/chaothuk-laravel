<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserMissionRequest extends FormRequest
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
            'points' => ['required', 'numeric', 'between:0,10000'],
            'banner_promotion_id' => ['required', 'numeric'],
        ];
    }
}
