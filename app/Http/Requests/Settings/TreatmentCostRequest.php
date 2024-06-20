<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentCostRequest extends FormRequest
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
        $treatmentCostId = $this->route('treatment_cost');

        return [
            'treat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('treatment_types')->ignore($treatmentCostId),
            ],
            'treat_cost' => [
                'required',
                'numeric',
                'min:0',
            ],
            'status' => 'required|string|size:1', 
        ];
    }

    public function messages()
    {
        return [
            'treat_name.required' => 'The treatment name is required.',
            'treat_name.string' => 'The treatment name must be a string.',
            'treat_name.max' => 'The treatment name may not be greater than 255 characters.',
            'treat_cost.required' => 'The treatment cost is required.',
            'treat_cost.numeric' => 'The treatment cost must be a number.',
            'treat_cost.min' => 'The treatment cost must be at least 0.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.size' => 'The status must be a single character.',
        ];
    }
}
