<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                
            ],
            'phone' => [
                'required',
                'string',  // Treat phone as a string
                'min:10',  // Minimum 10 digits
                'max:12',  // Maximum 12 digits
                'regex:/^[0-9]+$/', // Ensure it contains only digits
            ],
            'gst' => [
                'required',
                'string',
                'min:15',
                'max:15',
                
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:255',
                
            ],
            'status' => 'required|string|size:1',
        ];

    }
    public function messages()
    {
        return [
            'name.required' => 'The  name is required.',
            'name.string' => 'The  name must be a string.',
            'name.max' => 'The  name may not be greater than 255 characters.',
            'name.min' => 'The  name may not be less than 3 characters.',

            'gst.string' => 'The gst no must be a string.',
            'gst.max' => 'The gst no may not be greater than 15 characters.',
            'gst.min' => 'The gst no may not be less than 15 characters.',
            
            'phone.required' => 'The phone is required.',
            'phone.min' => 'The phoneno  may not be less than 10 characters.',
            'phone.max' => 'The phoneno  may not be greater than 12 characters.',

            'address.required' => 'The department name is required.',
            'address.string' => 'The department name must be a string.',
            'address.max' => 'The department name may not be greater than 255 characters.',
            'address.min' => 'The department name may not be less than 10 characters.',

            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }
}
