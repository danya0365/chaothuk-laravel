<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * booking_date is a plain calendar day here — the old API demanded "Y-m-d" while
     * its own docs showed a datetime, so clients could not tell which to send.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'customer_message' => ['required', 'string', 'min:5', 'max:1000'],
            'mobile_phone' => ['required', 'string', 'min:9', 'max:15'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'booking_date.required' => 'กรุณาเลือกวันที่ต้องการ',
            'booking_date.date_format' => 'รูปแบบวันที่ไม่ถูกต้อง (ต้องเป็น YYYY-MM-DD)',
            'booking_date.after_or_equal' => 'วันที่ต้องการต้องไม่ย้อนหลัง',
            'customer_message.required' => 'กรุณาเขียนข้อความถึงผู้ให้บริการ',
            'customer_message.min' => 'ข้อความต้องมีอย่างน้อย 5 ตัวอักษร',
            'mobile_phone.required' => 'กรุณากรอกเบอร์โทรศัพท์ที่ติดต่อได้',
            'mobile_phone.min' => 'เบอร์โทรศัพท์ต้องมีอย่างน้อย 9 หลัก',
        ];
    }
}
