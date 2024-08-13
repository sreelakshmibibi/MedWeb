<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientEditRequest extends FormRequest
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
        // return [
        //     'title' => [
        //         'required',
        //         'string',
        //         'max:10',
        //     ],
        //     'firstname' => [
        //         'required',
        //         'string',
        //         'min:3',
        //         'max:255',
        //     ],
        //     'lastname' => [
        //         'required',
        //         'string',
        //         'max:255',
        //     ],
        //     'gender' => [
        //         'required',
        //         'string',
        //         'max:10',
        //     ],
        //     'date_of_birth' => [
        //         'required',
        //         'date',
        //         'before:today',
        //         'after:'.now()->subYears(130)->format('Y-m-d'),
        //     ],
        //     'aadhaar_no' => [
        //         'nullable',
        //         'digits:12',
        //         Rule::unique('patient_profiles')->ignore($this->edit_patient_id, 'id'),
        //     ],
        //     'email' => [
        //         'nullable',
        //         'string',
        //         'email',
        //         'max:255',
        //     ],
        //     'phone' => [
        //         'required',
        //         'numeric',
        //         'digits_between:10,15',
        //     ],
        //     'alter_phone' => [
        //         'nullable',
        //         'numeric',
        //         'digits_between:10,15',
        //     ],
        //     'address1' => [
        //         'required',
        //         'string',
        //         'min:3',
        //         'max:255',
        //     ],
        //     'address2' => [
        //         'required',
        //         'string',
        //         'min:3',
        //         'max:255',
        //     ],
        //     'country_id' => [
        //         'required',
        //         'integer',
        //         'exists:countries,id',
        //     ],
        //     'state_id' => [
        //         'required',
        //         'integer',
        //         'exists:states,id',
        //     ],
        //     'city_id' => [
        //         'required',
        //         'integer',
        //         'exists:cities,id',
        //     ],
        //     'pincode' => [
        //         'required',
        //         'digits_between:3,10',
        //     ],
        //     'blood_group' => [
        //         'nullable',
        //         'string',
        //         'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        //     ],
        //     'marital_status' => ['nullable', 'string', 'max:20'],
        //     // 'smoking_status' => ['nullable', 'string', 'max:50'],
        //     // 'alcoholic_status' => ['nullable', 'string', 'max:30'],
        //     // 'diet' => ['nullable', 'string', 'max:20'],
        //     // 'allergies' => ['nullable', 'string', 'max:500'],
        //     // 'pregnant' => ['nullable', 'string'],
        // ];
        $rules = [
            'title' => [
                'required',
                'string',
                'max:10',
            ],
            'firstname' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'gender' => [
                'required',
                'string',
                'max:10',
            ],
            'date_of_birth' => [
                'required',
                'date',
                'before:today',
                'after:'.now()->subYears(130)->format('Y-m-d'),
            ],
            'aadhaar_no' => [
                'nullable',
                'digits:12',
                Rule::unique('patient_profiles')->ignore($this->edit_patient_id, 'id'),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
            ],
            'phone' => [
                'required',
                'numeric',
                'digits_between:10,15',
            ],
            'alter_phone' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
            ],
            'address1' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'address2' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],
            'state_id' => [
                'required',
                'integer',
                'exists:states,id',
            ],
            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],
            'pincode' => [
                'required',
                'digits_between:3,10',
            ],
            'blood_group' => [
                'nullable',
                'string',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            ],
            'marital_status' => ['nullable', 'string', 'max:20'],
        ];

        if ($this->input('policy_number') || $this->input('insurance_company_id')) {
            $rules['policy_number'] = ['required', 'string', 'max:255'];
            $rules['insurance_company_id'] = ['required', 'exists:insurance_companies,id'];
            $rules['policy_end_date'] = ['required', 'date', 'after_or_equal:today'];
            $rules['status'] = ['required', 'in:Y,N'];
            // 'insured_name' => 'nullable|string|max:255',
            // 'insured_dob' => 'nullable|date|before:today',
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 10 characters.',
            'firstname.required' => 'The first name field is required.',
            'firstname.string' => 'The first name must be a string.',
            'firstname.min' => 'The first name must be at least 3 characters.',
            'firstname.max' => 'The first name may not be greater than 255 characters.',
            'lastname.required' => 'The last name field is required.',
            'lastname.string' => 'The last name must be a string.',
            'lastname.max' => 'The last name may not be greater than 255 characters.',
            'gender.required' => 'The gender field is required.',
            'gender.string' => 'The gender must be a string.',
            'gender.max' => 'The gender may not be greater than 10 characters.',
            'date_of_birth.required' => 'The date of birth field is required.',
            'date_of_birth.date' => 'The date of birth is not a valid date.',
            'date_of_birth.before' => 'The date of birth must be before today.',
            'date_of_birth.after' => 'The date of birth must be after '.now()->subYears(130)->format('Y-m-d').'.',
            'aadhaar_no.digits' => 'The Aadhaar number must be 12 digits.',
            'aadhaar_no.unique' => 'The Aadhaar number has already been taken.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'phone.required' => 'The phone number field is required.',
            'phone.numeric' => 'The phone number must be a number.',
            'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',
            'alter_phone.numeric' => 'The alternate phone number must be a number.',
            'alter_phone.digits_between' => 'The alternate phone number must be between 10 and 15 digits.',
            'address1.required' => 'The address line 1 field is required.',
            'address1.string' => 'The address line 1 must be a string.',
            'address1.min' => 'The address line 1 must be at least 3 characters.',
            'address1.max' => 'The address line 1 may not be greater than 255 characters.',
            'address2.required' => 'The address line 2 field is required.',
            'address2.string' => 'The address line 2 must be a string.',
            'address2.min' => 'The address line 2 must be at least 3 characters.',
            'address2.max' => 'The address line 2 may not be greater than 255 characters.',
            'country_id.required' => 'The country field is required.',
            'country_id.integer' => 'The country must be an integer.',
            'country_id.exists' => 'The selected country is invalid.',
            'state_id.required' => 'The state field is required.',
            'state_id.integer' => 'The state must be an integer.',
            'state_id.exists' => 'The selected state is invalid.',
            'city_id.required' => 'The city field is required.',
            'city_id.integer' => 'The city must be an integer.',
            'city_id.exists' => 'The selected city is invalid.',
            'pincode.required' => 'The pincode field is required.',
            'pincode.digits_between' => 'The pincode must be between 3 and 10 digits.',
            'blood_group.string' => 'The blood group must be a string.',
            'blood_group.in' => 'The blood group must be one of the following types: A+, A-, B+, B-, AB+, AB-, O+, O-',
            'marital_status.string' => 'The marital status must be a string.',
            'marital_status.max' => 'The marital status may not be greater than 20 characters.',
            'policy_number.required' => 'The policy number field is required.',
            'policy_number.unique' => 'The policy number has already been taken.',
            'insurance_company_id.required' => 'The insurance company field is required.',
            'insurance_company_id.exists' => 'The selected insurance company is invalid.',
            'policy_end_date.required' => 'The policy end date field is required.',
            'policy_end_date.date' => 'The policy end date is not a valid date.',
            'policy_end_date.after_or_equal' => 'The policy end date must be a date after or equal to today.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either Y or N.',
        ];
    }
}
