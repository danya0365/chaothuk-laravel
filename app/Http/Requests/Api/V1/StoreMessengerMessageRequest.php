<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessengerMessageRequest extends FormRequest
{
    /**
     * Membership is checked in the controller: it depends on the channel in the
     * route, which this request cannot see.
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
            // The column is a `text`, so the database imposes no limit at all —
            // without this a client could post an unbounded payload.
            'content' => ['required', 'string', 'max:5000'],
            // Client-generated so a message survives a retry without being sent
            // twice; the server does not require it.
            'local_code_id' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:text,image,youtube,url,emoticon'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'กรุณาพิมพ์ข้อความ',
            'content.max' => 'ข้อความยาวเกินไป (ไม่เกิน 5,000 ตัวอักษร)',
            'type.in' => 'ประเภทข้อความไม่ถูกต้อง',
        ];
    }
}
