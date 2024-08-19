<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequest extends FormRequest
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
        // $medicineId = $this->route('medicine');

        // return [
        //     'med_bar_code' => [
        //         'required',
        //         'string',
        //         'max:100',
        //         Rule::unique('medicines')->ignore($medicineId), // Ensure the barcode is unique
        //     ],
        //     'med_name' => [
        //         'required',
        //         'string',
        //         'max:200',
        //     ],
        //     'med_company' => [
        //         'required',
        //         'string',
        //         'max:200',
        //     ],
        //     'med_remarks' => [
        //         'nullable',
        //         'string',
        //     ],
        //     'med_price' => [
        //         'required',
        //         'numeric',
        //         'min:0',
        //     ],
        //     'expiry_date' => [
        //         'required',
        //         'date',
        //         'after:today', // Ensure expiry date is in the future
        //     ],
        //     'units_per_package' => [
        //         'required',
        //         'integer',
        //         'min:1',
        //     ],
        //     'package_count' => [
        //         'required',
        //         'integer',
        //         'min:1',
        //     ],
        //     'total_quantity' => [
        //         'required',
        //         'integer',
        //         'min:0',
        //     ],
        //     'package_type' => [
        //         'required',
        //         'string',
        //         'max:50',
        //     ],
        //     'stock_status' => [
        //         'required',
        //         'string',
        //         'max:20',
        //     ],
        //     'status' => [
        //         'required',
        //         'string',
        //         'size:1',
        //     ],
        // ];
    }

    public function messages()
    {
        // return [
        //     'med_bar_code.required' => 'The medicine barcode is required.',
        //     'med_bar_code.string' => 'The barcode must be a string.',
        //     'med_bar_code.max' => 'The barcode may not be greater than 100 characters.',
        //     'med_bar_code.unique' => 'The medicine barcode must be unique.',

        //     'med_name.required' => 'The medicine name is required.',
        //     'med_name.string' => 'The medicine name must be a string.',
        //     'med_name.max' => 'The medicine name may not be greater than 200 characters.',

        //     'med_company.required' => 'The medicine company name is required.',
        //     'med_company.string' => 'The medicine company name must be a string.',
        //     'med_company.max' => 'The medicine company name may not be greater than 200 characters.',

        //     'med_price.required' => 'The medicine price is required.',
        //     'med_price.numeric' => 'The medicine price must be a number.',
        //     'med_price.min' => 'The medicine price must be at least 0.',

        //     'expiry_date.required' => 'The expiry date is required.',
        //     'expiry_date.date' => 'The expiry date must be a valid date.',
        //     'expiry_date.after' => 'The expiry date must be a future date.',

        //     'units_per_package.required' => 'The units per package are required.',
        //     'units_per_package.integer' => 'The units per package must be an integer.',
        //     'units_per_package.min' => 'The units per package must be at least 1.',

        //     'package_count.required' => 'The package count is required.',
        //     'package_count.integer' => 'The package count must be an integer.',
        //     'package_count.min' => 'The package count must be at least 1.',

        //     'total_quantity.required' => 'The total quantity is required.',
        //     'total_quantity.integer' => 'The total quantity must be an integer.',
        //     'total_quantity.min' => 'The total quantity must be at least 0.',

        //     'package_type.required' => 'The package type is required.',
        //     'package_type.string' => 'The package type must be a string.',
        //     'package_type.max' => 'The package type may not be greater than 50 characters.',

        //     'stock_status.required' => 'The stock status is required.',
        //     'stock_status.string' => 'The stock status must be a string.',
        //     'stock_status.max' => 'The stock status may not be greater than 20 characters.',

        //     'status.required' => 'The status is required.',
        //     'status.string' => 'The status must be a string.',
        //     'status.size' => 'The status must be a single character.',

        //     'med_remarks.string' => 'The medicine remarks must be a string.',
        // ];
    }
}
