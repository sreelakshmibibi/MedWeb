<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InsuranceCompanyRequest extends FormRequest
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
            'company_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('insurance_companies'),
            ],
            'claim_type' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => 'required|string|size:1',
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'The insurance company name is required.',
            'company_name.string' => 'The insurance company name must be a string.',
            'company_name.max' => 'The insurance company name may not be greater than 255 characters.',
            'company_name.unique' => 'The insurance company name has already been taken for this clinic type.',
            'claim_type.required' => 'The claim type is required.',
            'claim_type.string' => 'The claim type must be a string.',
            'claim_type.max' => 'The claim type may not be greater than 255 characters.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }

}