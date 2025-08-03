<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city' => 'required|string|max:255',
            'skill_level' => 'required|in:beginner,intermediate,advanced,pro',
            'bio' => 'nullable|string|max:1000',
            'availability' => 'nullable|array',
            'looking_for_partner' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'city.required' => 'Please enter your city.',
            'skill_level.required' => 'Please select your skill level.',
            'skill_level.in' => 'Please select a valid skill level.',
            'bio.max' => 'Bio cannot exceed 1000 characters.',
        ];
    }
}