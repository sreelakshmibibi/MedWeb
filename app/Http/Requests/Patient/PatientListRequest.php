<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientListRequest extends FormRequest
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
    // public function rules()
    // {
    //     return [
    //         'title' => [
    //             'required',
    //             'string',
    //             'max:10',
    //         ],
    //         'firstname' => [
    //             'required',
    //             'string',
    //             'min:3',
    //             'max:255',
    //         ],
    //         'lastname' => [
    //             'required',
    //             'string',
    //             'max:255',
    //         ],
    //         'gender' => [
    //             'required',
    //             'string',
    //             'max:10',
    //         ],
    //         'date_of_birth' => [
    //             'required',
    //             'date',
    //             'before:today',
    //             'after:' . now()->subYears(130)->format('Y-m-d'),
    //         ],
    //         'aadhaar_no' => [
    //             'nullable',
    //             'digits:12',
    //             Rule::unique('patient_profiles')->ignore($this->edit_patient_id, 'id'),
    //         ],
    //         'email' => [
    //             'nullable',
    //             'string',
    //             'email',
    //             'max:255',
    //         ],
    //         'phone' => [
    //             'required',
    //             'numeric',
    //             'digits_between:10,15',
    //         ],
    //         'alter_phone' => [
    //             'nullable',
    //             'numeric',
    //             'digits_between:10,15',
    //         ],
    //         'regdate' => [
    //             'required',
    //             'date',
    //         ],
    //         'address1' => [
    //             'required',
    //             'string',
    //             'min:3',
    //             'max:255',
    //         ],
    //         'address2' => [
    //             'required',
    //             'string',
    //             'min:3',
    //             'max:255',
    //         ],
    //         'country_id' => [
    //             'required',
    //             'integer',
    //             'exists:countries,id',
    //         ],
    //         'state_id' => [
    //             'required',
    //             'integer',
    //             'exists:states,id',
    //         ],
    //         'city_id' => [
    //             'required',
    //             'integer',
    //             'exists:cities,id',
    //         ],
    //         'pincode' => [
    //             'required',
    //             'digits_between:3,10',
    //         ],
    //         'blood_group' => [
    //             'nullable',
    //             'string',
    //             'in:A+,A-,B+,B-,AB+,AB-,O+,O-',
    //         ],
    //         'clinic_branch_id0' => [
    //             'required',
    //             'integer',
    //             'exists:clinic_branches,id',
    //         ],
    //         'doctor2' => [
    //             'required',
    //             'integer',
    //             'exists:users,id',
    //         ],
    //         'appdate' => [
    //             'required',
    //         ],
    //         'appstatus' => [
    //             'required',
    //             'integer',
    //             'exists:appointment_statuses,id',
    //         ],
    //         'height' => [
    //             'nullable',
    //             'numeric',
    //         ],
    //         'weight' => [
    //             'nullable',
    //             'numeric',
    //         ],
    //         'bp' => [
    //             'nullable',
    //             'regex:/^\d{2,3}\/\d{2,3}$/',
    //         ],
    //         'rdoctor' => [
    //             'nullable',
    //             'string',
    //         ],
    //         'marital_status' => ['nullable', 'string', 'max:20'],
    //         'smoking_status' => ['nullable', 'string', 'max:50'],
    //         'alcoholic_status' => ['nullable', 'string', 'max:30'],
    //         'diet' => ['nullable', 'string', 'max:20'],
    //         'allergies' => ['nullable', 'string'],
    //         'pregnant' => ['nullable', 'string'],
    //         'temperature' => ['nullable', 'numeric', 'min:90', 'max:110'],
    //         'paymode' => ['nullable', 'string', 'in:Cash,Card,GPay'],
    //         'regfee' => ['nullable', 'numeric', 'min:0'],
    //         'cardmachine' => ['nullable', 'exists:card_pays,id'],
    //     ];
    // }

    public function rules()
{
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
            'after:' . now()->subYears(130)->format('Y-m-d'),
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
        'regdate' => [
            'required',
            'date',
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
        'clinic_branch_id0' => [
            'required',
            'integer',
            'exists:clinic_branches,id',
        ],
        'doctor2' => [
            'required',
            'integer',
            'exists:users,id',
        ],
        'appdate' => [
            'required',
        ],
        'appstatus' => [
            'required',
            'integer',
            'exists:appointment_statuses,id',
        ],
        'height' => [
            'nullable',
            'numeric',
        ],
        'weight' => [
            'nullable',
            'numeric',
        ],
        'bp' => [
            'nullable',
            'regex:/^\d{2,3}\/\d{2,3}$/',
        ],
        'rdoctor' => [
            'nullable',
            'string',
        ],
        'marital_status' => ['nullable', 'string', 'max:20'],
        'smoking_status' => ['nullable', 'string', 'max:50'],
        'alcoholic_status' => ['nullable', 'string', 'max:30'],
        'diet' => ['nullable', 'string', 'max:20'],
        'allergies' => ['nullable', 'string'],
        'pregnant' => ['nullable', 'string'],
        'temperature' => ['nullable', 'numeric', 'min:90', 'max:110'],
        'paymode' => [
            'nullable',
            'string',
            'in:Cash,Card,GPay',
            Rule::requiredIf(function () {
                return $this->input('regfee') > 0;
            }),
        ],
        'regfee' => ['nullable', 'numeric', 'min:0'],
        'cardmachine' => [
            'nullable',
            Rule::requiredIf(function () {
                return $this->input('paymode') === 'Card';
            }),
            'exists:card_pays,id',
        ],
    ];

    return $rules;
}


    // public function messages()
    // {
    //     return [
    //         'title.required' => 'The title field is required.',
    //         'title.string' => 'The title must be a string.',
    //         'title.max' => 'The title may not be greater than 10 characters.',
    //         'firstname.required' => 'The first name field is required.',
    //         'firstname.string' => 'The first name must be a string.',
    //         'firstname.min' => 'The first name must be at least 3 characters.',
    //         'firstname.max' => 'The first name may not be greater than 255 characters.',
    //         'lastname.required' => 'The last name field is required.',
    //         'lastname.string' => 'The last name must be a string.',
    //         'lastname.max' => 'The last name may not be greater than 255 characters.',
    //         'gender.required' => 'The gender field is required.',
    //         'gender.string' => 'The gender must be a string.',
    //         'gender.max' => 'The gender may not be greater than 10 characters.',
    //         'date_of_birth.required' => 'The date of birth field is required.',
    //         'date_of_birth.date' => 'The date of birth is not a valid date.',
    //         'date_of_birth.before' => 'The date of birth must be before today.',
    //         'date_of_birth.after' => 'The date of birth must be after ' . now()->subYears(130)->format('Y-m-d') . '.',
    //         'aadhaar_no.digits' => 'The Aadhaar number must be 12 digits.',
    //         'aadhaar_no.unique' => 'The Aadhaar number has already been taken.',
    //         'email.email' => 'The email must be a valid email address.',
    //         'email.max' => 'The email may not be greater than 255 characters.',
    //         'phone.required' => 'The phone number field is required.',
    //         'phone.numeric' => 'The phone number must be a number.',
    //         'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',
    //         'alter_phone.numeric' => 'The alternate phone number must be a number.',
    //         'alter_phone.digits_between' => 'The alternate phone number must be between 10 and 15 digits.',
    //         'regdate.required' => 'The registration date field is required.',
    //         'regdate.date' => 'The registration date is not a valid date.',
    //         'address1.required' => 'The address line 1 field is required.',
    //         'address1.string' => 'The address line 1 must be a string.',
    //         'address1.min' => 'The address line 1 must be at least 3 characters.',
    //         'address1.max' => 'The address line 1 may not be greater than 255 characters.',
    //         'address2.required' => 'The address line 2 field is required.',
    //         'address2.string' => 'The address line 2 must be a string.',
    //         'address2.min' => 'The address line 2 must be at least 3 characters.',
    //         'address2.max' => 'The address line 2 may not be greater than 255 characters.',
    //         'country_id.required' => 'The country field is required.',
    //         'country_id.integer' => 'The country must be an integer.',
    //         'country_id.exists' => 'The selected country is invalid.',
    //         'state_id.required' => 'The state field is required.',
    //         'state_id.integer' => 'The state must be an integer.',
    //         'state_id.exists' => 'The selected state is invalid.',
    //         'city_id.required' => 'The city field is required.',
    //         'city_id.integer' => 'The city must be an integer.',
    //         'city_id.exists' => 'The selected city is invalid.',
    //         'pincode.required' => 'The pincode field is required.',
    //         'pincode.digits_between' => 'The pincode must be between 3 and 10 digits.',
    //         'blood_group.string' => 'The blood group must be a string.',
    //         'blood_group.in' => 'The blood group must be one of the following types: A+, A-, B+, B-, AB+, AB-, O+, O-',
    //         'clinic_branch_id0.required' => 'The clinic branch field is required.',
    //         'clinic_branch_id0.integer' => 'The clinic branch must be an integer.',
    //         'clinic_branch_id0.exists' => 'The selected clinic branch is invalid.',
    //         'doctor2.required' => 'The doctor field is required.',
    //         'doctor2.integer' => 'The doctor must be an integer.',
    //         'doctor2.exists' => 'The selected doctor is invalid.',
    //         'appdate.required' => 'The appointment date field is required.',
    //         'appdate.date_format' => 'The appointment date must be in the format Y-m-d H:i:s.',
    //         'appstatus.required' => 'The appointment status field is required.',
    //         'appstatus.integer' => 'The appointment status must be an integer.',
    //         'appstatus.exists' => 'The selected appointment status is invalid.',
    //         'height.numeric' => 'The height must be a number.',
    //         'weight.numeric' => 'The weight must be a number.',
    //         'bp.regex' => 'The blood pressure must be in the format XX/YY or XXX/YYY.',
    //         'rdoctor.string' => 'The referred doctor must be a string.',
    //         'marital_status.string' => 'The marital status must be a string.',
    //         'marital_status.max' => 'The marital status may not be greater than 20 characters.',
    //         'smoking_status.string' => 'The smoking status must be a string.',
    //         'smoking_status.max' => 'The smoking status may not be greater than 50 characters.',
    //         'alcoholic_status.string' => 'The alcoholic status must be a string.',
    //         'alcoholic_status.max' => 'The alcoholic status may not be greater than 30 characters.',
    //         'diet.string' => 'The diet must be a string.',
    //         'diet.max' => 'The diet may not be greater than 20 characters.',
    //         'allergies.string' => 'The allergies must be a string.',
    //         'allergies.max' => 'The allergies may not be greater than 500 characters.',
    //         'pregnant.string' => 'The pregnant field must be a string.',
    //         'temperature.numeric' => 'The temperature must be a number.',
    //         'temperature.min' => 'The temperature must be at least 90 degrees.',
    //         'temperature.max' => 'The temperature may not be greater than 110 degrees.',
    //         'paymode.in' => 'The payment mode must be one of the following: Cash, Card, or GPay.',
    //         'regfee.numeric' => 'The registration fee must be a numeric value.',
    //         'regfee.min' => 'The registration fee cannot be negative.',
    //         'cardmachine.exists' => 'The selected card machine does not exist in our records.',
    //     ];
    // }

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
        'date_of_birth.after' => 'The date of birth must be after ' . now()->subYears(130)->format('Y-m-d') . '.',
        'aadhaar_no.digits' => 'The Aadhaar number must be 12 digits.',
        'aadhaar_no.unique' => 'The Aadhaar number has already been taken.',
        'email.email' => 'The email must be a valid email address.',
        'email.max' => 'The email may not be greater than 255 characters.',
        'phone.required' => 'The phone number field is required.',
        'phone.numeric' => 'The phone number must be a number.',
        'phone.digits_between' => 'The phone number must be between 10 and 15 digits.',
        'alter_phone.numeric' => 'The alternate phone number must be a number.',
        'alter_phone.digits_between' => 'The alternate phone number must be between 10 and 15 digits.',
        'regdate.required' => 'The registration date field is required.',
        'regdate.date' => 'The registration date is not a valid date.',
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
        'clinic_branch_id0.required' => 'The clinic branch field is required.',
        'clinic_branch_id0.integer' => 'The clinic branch must be an integer.',
        'clinic_branch_id0.exists' => 'The selected clinic branch is invalid.',
        'doctor2.required' => 'The doctor field is required.',
        'doctor2.integer' => 'The doctor must be an integer.',
        'doctor2.exists' => 'The selected doctor is invalid.',
        'appdate.required' => 'The appointment date field is required.',
        'appstatus.required' => 'The appointment status field is required.',
        'appstatus.integer' => 'The appointment status must be an integer.',
        'appstatus.exists' => 'The selected appointment status is invalid.',
        'height.numeric' => 'The height must be a number.',
        'weight.numeric' => 'The weight must be a number.',
        'bp.regex' => 'The blood pressure must be in the format XX/YY or XXX/YYY.',
        'rdoctor.string' => 'The referred doctor field must be a string.',
        'marital_status.string' => 'The marital status must be a string.',
        'marital_status.max' => 'The marital status may not be greater than 20 characters.',
        'smoking_status.string' => 'The smoking status must be a string.',
        'smoking_status.max' => 'The smoking status may not be greater than 50 characters.',
        'alcoholic_status.string' => 'The alcoholic status must be a string.',
        'alcoholic_status.max' => 'The alcoholic status may not be greater than 30 characters.',
        'diet.string' => 'The diet must be a string.',
        'diet.max' => 'The diet may not be greater than 20 characters.',
        'allergies.string' => 'The allergies field must be a string.',
        'pregnant.string' => 'The pregnant field must be a string.',
        'temperature.numeric' => 'The temperature must be a number.',
        'temperature.min' => 'The temperature must be at least 90.',
        'temperature.max' => 'The temperature may not be greater than 110.',
        'paymode.required' => 'The payment mode is required when the registration fee is greater than 0.',
        'paymode.string' => 'The payment mode must be a string.',
        'paymode.in' => 'The payment mode must be one of the following types: Cash, Card, GPay.',
        'regfee.numeric' => 'The registration fee must be a number.',
        'regfee.min' => 'The registration fee must be at least 0.',
        'cardmachine.required_if' => 'The card machine is required when the payment mode is Card.',
        'cardmachine.exists' => 'The selected card machine is invalid.',
        'cardmachine.required' => 'The card machine field is required when the payment mode is Card.',
    ];
}

}
