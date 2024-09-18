<?php

namespace App\Http\Requests;

use App\Enums\MissionStatus;
use Illuminate\Foundation\Http\FormRequest;

class UserMissionForStatusRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . implode(',', MissionStatus::values())],
            'note' => ['nullable', 'string']
        ];
    }
}
