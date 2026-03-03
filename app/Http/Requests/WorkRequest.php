<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['sometimes', 'required', 'string', Rule::unique('works', 'code')->ignore($this->work)],
            'title' => ['sometimes', 'required', 'string'],
            'description' => ['sometimes', 'nullable', 'string'],
            'details' => ['sometimes', 'nullable', 'string'],
            'primary_image' => ['sometimes', 'required', 'url:http,https'],
            'images' => ['sometimes', 'required', 'array'],
            'province_id' => ['sometimes', 'required', 'string'],
            'work_type_id' => ['sometimes', 'required', 'string'],
            'author_id' => ['sometimes', 'required', 'string'],
            'display_priority' => ['sometimes', 'required', 'numeric', 'between:0,10000000'],
            'price' => ['sometimes', 'required', 'numeric', 'between:0,10000000'],
        ];
    }
}
