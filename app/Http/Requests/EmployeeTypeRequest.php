<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeTypeRequest extends FormRequest
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
    public function rules()
    { 
        return [
        'employee_type' => [
            'required',
            'string',
            'max:255',
            Rule::unique('employee_types'),
        ],
        'status' => 'required|string|size:1',
    ];
    }

    public function messages()
    {
        return [
            'employee_type.required' => 'The employee type name is required.',
            'employee_type.string' => 'The employee type name must be a string.',
            'employee_type.max' => 'The employee type name may not be greater than 255 characters.',
            'employee_type.unique' => 'The employee type name has already been taken.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }
}
