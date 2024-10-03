<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ClinicBasicDetailRequest extends FormRequest
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
            'clinic_name' => 'required|string|max:255',
            'insurance' => 'required|string|max:255',
            'consultation_fees' => 'required|integer|max:999',
            'patient_registration_fees' => 'required|integer|max:999',
            'consultation_fees_frequency' => 'required|integer|max:30',
            'clinic_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clinic_website' => 'nullable|url|max:255',
            // 'financial_year_start' => 'required',
            // 'financial_year_end' => 'required',
         
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
            'clinic_name.required' => 'Clinic name is required.',
            'clinic_name.string' => 'Clinic name must be a string.',
            'clinic_name.max' => 'Clinic name should not be greater than :max characters.',

            'clinic_logo.image' => 'Clinic logo must be an image.',
            'clinic_logo.mimes' => 'Supported image formats are jpeg, png, jpg, gif.',
            'clinic_logo.max' => 'Maximum size allowed for the logo is :max kilobytes.',

            'clinic_website.url' => 'Invalid website URL.',
            'clinic_website.max' => 'Website URL should not be greater than :max characters.',
        ];

    }
}
