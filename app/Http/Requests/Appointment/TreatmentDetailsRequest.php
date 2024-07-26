<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentDetailsRequest extends FormRequest
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
        $rules = [];
        if ($this->input('follow_checkbox')) {
            $rules = [
                'appdate' => 'required|date_format:Y-m-d\TH:i',
                'doctor_id' => 'required|exists:users,id',
                'clinic_branch_id' => 'required|exists:clinic_branches,id',
                'remarks_followup' => 'nullable|string',
            ];
        }

        // Add rules for 'discount1' if the user has the 'Admin' role
        if (auth()->user()->hasRole('Admin')) {
            $rules['discount1'] = 'nullable|numeric|min:0|max:100'; // Adjust validation rules for discount1 as needed
        }

        if ($this->input('presc_checkbox')) {
            $prescriptionCount = count($this->input('prescriptions', []));

            $rules['prescriptions.*.medicine_id'] = 'required';
            $rules['prescriptions.*.dosage_id'] = 'required|exists:dosages,id';
            $rules['prescriptions.*.duration'] = 'required|integer|min:1';
            $rules['prescriptions.*.advice'] = 'required|string';
            $rules['prescriptions.*.remark'] = 'nullable|string|max:300';
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'appdate.required' => 'The appointment date is required.',
            'appdate.date_format' => 'The appointment date must be in the format YYYY-MM-DDTHH:MM.',
            'doctor_id.required' => 'The doctor field is required.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'clinic_branch_id.required' => 'The clinic branch field is required.',
            'clinic_branch_id.exists' => 'The selected clinic branch does not exist.',
            'remarks_followup.string' => 'The follow-up remarks must be a string.',
            'discount1.numeric' => 'The discount must be a numeric value.',
            'discount1.min' => 'The discount must be at least 0.',
            'discount1.max' => 'The discount maximum must be 100.',
            'prescriptions.*.medicine_id.required' => 'The medicine field is required for each prescription.',
            'prescriptions.*.dosage_id.exists' => 'The selected dosage does not exist.',
            'prescriptions.*.dosage_id.required' => 'The dosage field is required.',
            'prescriptions.*.duration.required' => 'The duration is required for each prescription.',
            'prescriptions.*.duration.integer' => 'The duration must be a valid integer.',
            'prescriptions.*.duration.min' => 'The duration must be at least 1 day.',
            'prescriptions.*.advice.required' => 'The advice field is required for each prescription.',
            'prescriptions.*.remark.string' => 'The remark must be a string.',
            'prescriptions.*.remark.max' => 'The remark may not be greater than 300 characters.',
        ];
    }
}
