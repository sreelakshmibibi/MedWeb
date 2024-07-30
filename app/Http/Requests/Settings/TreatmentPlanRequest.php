<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentPlanRequest extends FormRequest
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
            'plan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('treatment_plans'),
            ],
            'cost' => [
                'required',
                'integer',
                
            ],
            'status' => 'required|string|size:1',
        ];
    }

    public function messages()
    {
        return [
            'plan.required' => 'The plan is required.',
            'plan.string' => 'The plan must be a string.',
            'plan.max' => 'The plan may not be greater than 255 characters.',
            'plan.unique' => 'The plan has already been taken for this clinic type.',
            'cost.required' => 'The cost is required.',
            'cost.string' => 'The cost must be an integer.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
        ];
    }

}
