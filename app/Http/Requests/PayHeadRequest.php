<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayHeadRequest extends FormRequest
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
        'head_type' => [
            'required',
            'string',
            'max:255',
            Rule::unique('pay_heads'),
        ],
        'status' => 'required|string|size:1',
    ];
    }

    public function messages()
    {
        return [
            'head_type.required' => 'The pay head type name is required.',
            'head_type.string' => 'The pay head type name must be a string.',
            'head_type.max' => 'The pay head type name may not be greater than 255 characters.',
            'head_type.unique' => 'The pay head type name has already been taken.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }

}
