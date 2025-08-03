<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTennisMatchRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'city' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'match_date' => 'required|date|after:now',
            'max_players' => 'required|integer|min:2|max:8',
            'skill_level' => 'required|in:all,beginner,intermediate,advanced,pro',
            'match_type' => 'required|in:singles,doubles,mixed',
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
            'title.required' => 'Please enter a match title.',
            'city.required' => 'Please enter the city.',
            'venue.required' => 'Please enter the venue.',
            'match_date.required' => 'Please select a match date.',
            'match_date.after' => 'Match date must be in the future.',
            'max_players.required' => 'Please specify maximum number of players.',
            'max_players.min' => 'Minimum 2 players required.',
            'max_players.max' => 'Maximum 8 players allowed.',
            'skill_level.required' => 'Please select skill level.',
            'match_type.required' => 'Please select match type.',
        ];
    }
}