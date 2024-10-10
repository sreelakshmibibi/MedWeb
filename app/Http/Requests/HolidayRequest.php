<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
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
            'holiday_on' => 'required|date|after_or_equal:today', // Ensure date is today or later
            'reason' => 'required|string|max:255',
            'branches' => 'nullable|array',
            'status' => 'required|string|in:Y,N',
        ];
    }

    public function messages(): array
    {
        return [
            'holiday_on.required' => 'The holiday date is required.',
            'holiday_on.date' => 'The holiday date must be a valid date.',
            'holiday_on.after_or_equal' => 'The holiday date must be today or a future date.',
            'reason.required' => 'The reason for the holiday is required.',
            'reason.string' => 'The reason must be a valid string.',
            'reason.max' => 'The reason may not be greater than 255 characters.',
            'branches.array' => 'The branches must be an array of selected branches.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either "Y" (Yes) or "N" (No).',
        ];
    }
}
