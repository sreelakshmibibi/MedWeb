<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicineRequest extends FormRequest
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
            'med_bar_code' => [
                'required',
                'string',
                'max:100',
                // Rule::unique('medicines'), // Ensure the barcode is unique
            ],
            'med_name' => [
                'required',
                'string',
                'max:200',
            ],
            'med_company' => [
                'required',
                'string',
                'max:200',
            ],
            'med_strength' => [
                'required',
                'integer',
                'min:1',
            ],
            'med_remarks' => [
                'nullable',
                'string',
            ],
            'med_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'expiry_date' => [
                'required',
                'date',
                'after:today', // Ensure expiry date is in the future
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'stock_status' => [
                'required',
                'string',
                'max:20',
            ],
            'status' => [
                'required',
                'string',
                'size:1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'med_bar_code.required' => 'The medicine barcode is required.',
            'med_bar_code.string' => 'The barcode must be a string.',
            'med_bar_code.max' => 'The barcode may not be greater than 100 characters.',
            'med_bar_code.unique' => 'The medicine barcode must be unique.',
            'med_name.required' => 'The medicine name is required.',
            'med_name.string' => 'The medicine name must be a string.',
            'med_name.max' => 'The medicine name may not be greater than 200 characters.',
            'med_company.required' => 'The medicine company is required.',
            'med_company.string' => 'The medicine company name must be a string.',
            'med_company.max' => 'The medicine company name may not be greater than 200 characters.',
            'med_strength.required' => 'The medicine strength is required.',
            'med_strength.integer' => 'The medicine strength must be a number.',
            'med_strength.min' => 'The medicine strength must be at least 1.',
            'med_price.required' => 'The medicine price is required.',
            'med_price.numeric' => 'The medicine price must be a number.',
            'med_price.min' => 'The medicine price must be at least 0.',
            'expiry_date.required' => 'The expiry date is required.',
            'expiry_date.date' => 'The expiry date  must be a date.',
            'expiry_date.after' => 'The expiry date must be a future date.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The medicine quantity must be a number.',
            'quantity.min' => 'The medicine quantity must be at least 1.',
            'stock_status.required' => 'The stock status is required.',
            'stock_status.string' => 'The stock status must be a string.',
            'stock_status.max' => 'The stock status may not be greater than 20 characters.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.size' => 'The status must be a single character.',
            'med_remarks.string' => 'The medicine remarks must be a string.',
        ];
    }
}
