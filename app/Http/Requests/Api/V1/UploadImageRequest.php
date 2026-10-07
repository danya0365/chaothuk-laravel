<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * The old /api/upload/* route accepted any mime type at all; pin it to images.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'image' => [
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
            'image.required' => 'กรุณาเลือกรูปภาพ',
            'image.image' => 'กรุณาเลือกเป็นไฟล์รูปภาพเท่านั้น',
            'image.mimes' => 'รองรับเฉพาะไฟล์ JPG, PNG และ WebP',
            'image.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 10 MB',
        ];
    }
}
