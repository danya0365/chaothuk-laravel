<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Enums\PersonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'numeric'],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', Rule::unique('users', 'email')->ignore($this->user_customer?->user_id, 'id')],
            'password' => ['nullable', 'string'],

            'profile_image' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string'],

            'person_type' => ['required', 'in:' . implode(',', PersonType::values())],
            'first_name' => ['required_if:person_type,natural', 'min:2', 'max:30', 'nullable'],
            'last_name' => ['required_if:person_type,natural', 'min:2', 'max:30', 'nullable'],
            'gender' => ['required_if:person_type,natural', 'in:' . implode(',', Gender::values()), 'nullable'],
            'identification_no' => ['required_if:person_type,natural', 'numeric', 'digits:13', 'nullable'],
            'birth_date' => ['required_if:person_type,natural', 'date_format:Y-m-d', 'nullable'],
            'mobile_phone' => ['required_if:person_type,natural', 'numeric',  'nullable'],

            'juristic_name' => ['required_if:person_type,juristic', 'min:2', 'max:30', 'nullable'],
            'juristic_id' => ['required_if:person_type,juristic', 'numeric', 'nullable'],
            'registration_date' => ['required_if:person_type,juristic', 'date_format:Y-m-d', 'nullable'],
            'contact_number' => ['required_if:person_type,juristic', 'numeric',  'nullable'],

            'referral_program' => ['nullable', 'numeric'],

            'user_types' => ['required']
        ];
    }
}
