<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiseaseRequest extends FormRequest
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
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('diseases'),
        ],
        'description'=> 'nullable|string|max:255',
        'status' => 'required|string|size:1',
    ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The disease name is required.',
            'name.string' => 'The disease name must be a string.',
            'name.max' => 'The disease name may not be greater than 255 characters.',
            'name.unique' => 'The disease name has already been taken for this clinic type.',

            'description.required' => 'The disease name is required.',
            'description.string' => 'The disease name must be a string.',
            'description.max' => 'The disease name may not be greater than 255 characters.',

            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }
}
