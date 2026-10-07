<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecruitRequest extends FormRequest
{
    /**
     * The permission check lives in the controller, matching the project's pattern.
     */
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
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'details' => ['nullable', 'array'],
            'budget' => ['required', 'numeric', 'min:0'],
            'primary_image' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'max:255'],
            'province_id' => ['required', 'integer', 'exists:provinces,id'],
            'work_type_id' => ['required', 'integer', 'exists:work_types,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'กรุณากรอกชื่อประกาศ',
            'title.min' => 'ชื่อประกาศต้องมีอย่างน้อย 5 ตัวอักษร',
            'description.required' => 'กรุณากรอกรายละเอียดงาน',
            'description.min' => 'รายละเอียดต้องมีอย่างน้อย 10 ตัวอักษร',
            'budget.required' => 'กรุณากรอกงบประมาณ',
            'province_id.required' => 'กรุณาเลือกจังหวัด',
            'province_id.exists' => 'จังหวัดที่เลือกไม่ถูกต้อง',
            'work_type_id.required' => 'กรุณาเลือกประเภทงาน',
            'work_type_id.exists' => 'ประเภทงานที่เลือกไม่ถูกต้อง',
        ];
    }
}
