<?php

namespace App\Http\Requests;

use App\Enums\PromotionType;
use Illuminate\Foundation\Http\FormRequest;

class BannerPromotionRequest extends FormRequest
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
            'type' => ['required', 'in:' . implode(',', PromotionType::values())],
            'name' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string'],
            'condition_text' => ['required_if:type,mission', 'string'],
            'tags' => ['required', 'string', 'max:255'],
            'image_url' => ['required', 'string', 'max:255'],
            'expired_at' => ['nullable', 'string'],
            'acquire_points' => ['required_if:type,mission', 'numeric', 'between:0,10000'],
            'available_missions' => ['required_if:type,mission', 'numeric', 'between:-1,10000'],
            'max_mission_per_user' => ['required_if:type,mission', 'numeric', 'between:-1,10000'],
            'user_types' => ['required_if:type,mission', 'array'],
        ];
    }
}
