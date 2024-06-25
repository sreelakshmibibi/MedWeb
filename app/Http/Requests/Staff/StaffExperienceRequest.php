<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StaffExperienceRequest extends FormRequest
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
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'department' => 'required',
            'designation' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'date_of_relieving' => 'nullable|date',
            'specialization' => 'required|string|max:255',
            'Subspeciality' => 'required|string|max:255',
            'sunday_from' => 'nullable|date_format:H:i',
            'sunday_to' => 'nullable|date_format:H:i',
            'monday_from' => 'nullable|date_format:H:i',
            'monday_to' => 'nullable|date_format:H:i',
            'tuesday_from' => 'nullable|date_format:H:i',
            'tuesday_to' => 'nullable|date_format:H:i',
            'wednesday_from' => 'nullable|date_format:H:i',
            'wednesday_to' => 'nullable|date_format:H:i',
            'thursday_from' => 'nullable|date_format:H:i',
            'thursday_to' => 'nullable|date_format:H:i',
            'friday_from' => 'nullable|date_format:H:i',
            'friday_to' => 'nullable|date_format:H:i',
            'saturday_from' => 'nullable|date_format:H:i',
            'saturday_to' => 'nullable|date_format:H:i',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'qualification.required' => 'The qualification field is required.',
            'qualification.string' => 'The qualification must be a string.',
            'qualification.max' => 'The qualification may not be greater than :max characters.',

            'experience.required' => 'The experience field is required.',
            'experience.string' => 'The experience must be a string.',
            'experience.max' => 'The experience may not be greater than :max characters.',

            'department.required' => 'The department field is required.',

            'designation.required' => 'The designation field is required.',
            'designation.string' => 'The designation must be a string.',
            'designation.max' => 'The designation may not be greater than :max characters.',

            'date_of_joining.required' => 'The date of joining field is required.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',

            'date_of_relieving.date' => 'The date of relieving must be a valid date.',

            'specialization.required' => 'The specialization field is required.',
            'specialization.string' => 'The specialization must be a string.',
            'specialization.max' => 'The specialization may not be greater than :max characters.',

            'Subspeciality.required' => 'The subspeciality field is required.',
            'Subspeciality.string' => 'The subspeciality must be a string.',
            'Subspeciality.max' => 'The subspeciality may not be greater than :max characters.',

            'sunday_from.date_format' => 'Invalid format for Sunday (from) time.',
            'sunday_to.date_format' => 'Invalid format for Sunday (to) time.',

            'monday_from.date_format' => 'Invalid format for Monday (from) time.',
            'monday_to.date_format' => 'Invalid format for Monday (to) time.',

            'tuesday_from.date_format' => 'Invalid format for Tuesday (from) time.',
            'tuesday_to.date_format' => 'Invalid format for Tuesday (to) time.',

            'wednesday_from.date_format' => 'Invalid format for Wednesday (from) time.',
            'wednesday_to.date_format' => 'Invalid format for Wednesday (to) time.',

            'thursday_from.date_format' => 'Invalid format for Thursday (from) time.',
            'thursday_to.date_format' => 'Invalid format for Thursday (to) time.',

            'friday_from.date_format' => 'Invalid format for Friday (from) time.',
            'friday_to.date_format' => 'Invalid format for Friday (to) time.',

            'saturday_from.date_format' => 'Invalid format for Saturday (from) time.',
            'saturday_to.date_format' => 'Invalid format for Saturday (to) time.',
        ];
    }
}
