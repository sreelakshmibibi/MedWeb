<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
        'department' => [
            'required',
            'string',
            'max:255',
            Rule::unique('departments'),
        ],
        'status' => 'required|string|size:1',
    ];
    }

    public function messages()
    {
        return [
            'department.required' => 'The department name is required.',
            'department.string' => 'The department name must be a string.',
            'department.max' => 'The department name may not be greater than 255 characters.',
            'department.unique' => 'The department name has already been taken for this clinic type.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }
}
