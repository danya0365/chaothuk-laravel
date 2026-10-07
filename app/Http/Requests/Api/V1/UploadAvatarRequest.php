<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'กรุณาเลือกรูปโปรไฟล์',
            'avatar.image' => 'กรุณาเลือกเป็นไฟล์รูปภาพเท่านั้น',
            'avatar.mimes' => 'รองรับเฉพาะไฟล์ JPG, PNG และ WebP',
            'avatar.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 10 MB',
        ];
    }
}
