<?php

namespace App\Http\Requests;

use App\Enums\IssueStatus;
use App\Enums\IssueType;
use Illuminate\Foundation\Http\FormRequest;

class IssuePointRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'desc' => ['required', 'string'],
            'points' => ['required', 'numeric', 'between:0,10000'],
            'type' => ['nullable', 'in:' . implode(',', IssueType::values())],
            //'status' => ['nullable', 'in:' . implode(',', IssueStatus::values())],
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'cron_task' => ['nullable', 'string'],
            'start_at' => ['nullable', 'string'],
            'end_at' => ['nullable', 'string'],
            'repeat_type' => ['nullable', 'string'],
            'repeat_value' => ['nullable', 'string'],
        ];
    }
}