<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicBranchRequest extends FormRequest
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
            'clinic_email' => 'nullable|email|max:255',
            'clinic_phone' => 'required|string|max:20',
            'clinic_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clinic_website' => 'nullable|url|max:255',
            'branch_active' => 'required|in:Y,N',
            'clinic_address1' => 'required|string|max:255',
            'clinic_address2' => 'nullable|string|max:255',
            'clinic_country' => 'required|exists:countries,id',
            'clinic_state' => 'required|exists:states,id',
            'clinic_city' => 'required|exists:cities,id',
            'clinic_pincode' => 'required|string|max:10',
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

            'clinic_email.email' => 'Invalid email address.',
            'clinic_email.max' => 'Email should not be greater than :max characters.',

            'clinic_phone.required' => 'Clinic contact number is required.',
            'clinic_phone.string' => 'Clinic contact number must be a string.',
            'clinic_phone.max' => 'Clinic contact number should not be greater than :max characters.',

            'clinic_logo.image' => 'Clinic logo must be an image.',
            'clinic_logo.mimes' => 'Supported image formats are jpeg, png, jpg, gif.',
            'clinic_logo.max' => 'Maximum size allowed for the logo is :max kilobytes.',

            'clinic_website.url' => 'Invalid website URL.',
            'clinic_website.max' => 'Website URL should not be greater than :max characters.',

            'branch_active.required' => 'Select whether it is the main branch.',
            'branch_active.in' => 'Invalid value for branch active status.',

            'clinic_address1.required' => 'Address Line 1 is required.',
            'clinic_address1.string' => 'Address Line 1 must be a string.',
            'clinic_address1.max' => 'Address Line 1 should not be greater than :max characters.',

            'clinic_address2.string' => 'Address Line 2 must be a string.',
            'clinic_address2.max' => 'Address Line 2 should not be greater than :max characters.',

            'clinic_country.required' => 'Select a country.',
            'clinic_country.exists' => 'Selected country is invalid.',

            'clinic_state.required' => 'Select a state.',
            'clinic_state.exists' => 'Selected state is invalid.',

            'clinic_city.required' => 'Select a city.',
            'clinic_city.exists' => 'Selected city is invalid.',

            'clinic_pincode.required' => 'Pin code is required.',
            'clinic_pincode.string' => 'Pin code must be a string.',
            'clinic_pincode.max' => 'Pin code should not be greater than :max characters.',
        ];

    }
}
