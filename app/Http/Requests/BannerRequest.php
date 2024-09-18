<?php

namespace App\Http\Requests;

use App\Enums\BannerType;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'view_count' => ['nullable', 'numeric', 'between:0,10000000'],
            'expired_at' => ['nullable', 'string'],
            'type' => ['required', 'in:' . implode(',', BannerType::values())],
            'image_url' => ['required', 'url', 'max:255'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'is_public' => ['required', 'numeric'],
            'banner_product_id' => ['nullable', 'numeric'],
            'banner_promotion_id' => ['nullable', 'numeric'],
            'is_pinned' => ['nullable', 'numeric'],
        ];
    }
}
