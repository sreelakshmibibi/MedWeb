<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
        //     'firstname' => 'required|string|max:255',
        //     'lastname' => 'required|string|max:255',
        //     'Gender' => 'required|in:M,F,O',
        //     'date_of_birth' => 'required|date',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'required|string|max:20',
        //     'address1' => 'required|string|max:255',
        //     'address2' => 'required|string|max:255',
        //     'country_id' => 'required|string|max:255',
        //     'state_id' => 'required|string|max:255',
        //     'city_id' => 'required|string|max:255',
        //     'pincode' => 'required|string|max:10',
        //     'role' => 'required',
        //     'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ];
        return [

            'doctor_id' => 'required|exists:users,id',
            'clinic_branch_id' => 'required|exists:clinic_branches,id',
            'patient_id' => 'required|exists:patient_profiles,patient_id',
            'app_parent_id' => 'nullable|exists:appointments,id',
            'appdate' => 'required|date_format:Y-m-d\TH:i',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'bp' => [
                'nullable',
                'regex:/^\d{2,3}\/\d{2,3}$/',
            ],
            'rdoctor' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'doctor_id.required' => 'The doctor field is required.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'clinic_branch_id.required' => 'The clinic branch field is required.',
            'clinic_branch_id.exists' => 'The selected clinic branch does not exist.',
            'patient_id.required' => 'The patient field is required.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'app_parent_id.exists' => 'The selected appointment parent ID does not exist.',
            'appdate.required' => 'The appointment date and time field is required.',
            'appdate.date_format' => 'The appointment date and time must be in the format YYYY-MM-DDTHH:MM.',
            'height.numeric' => 'The height must be a number.',
            'height.min' => 'The height must be at least 0.',
            'weight.numeric' => 'The weight must be a number.',
            'weight.min' => 'The weight must be at least 0.',
            'bp.regex' => 'The blood pressure format is invalid. It should be in the format nnn/nnn.',
            'rdoctor.string' => 'The referred doctor name must be a string.',
            'rdoctor.max' => 'The referred doctor name must not be more than 255 characters.',
        ];
    }
    // public function messages()
    // {
    // return [
    //     'firstname.required' => 'The first name field is required.',
    //     'firstname.string' => 'The first name must be a string.',
    //     'firstname.max' => 'The first name may not be greater than :max characters.',

    //     'lastname.required' => 'The last name field is required.',
    //     'lastname.string' => 'The last name must be a string.',
    //     'lastname.max' => 'The last name may not be greater than :max characters.',

    //     'Gender.required' => 'The gender field is required.',
    //     'Gender.in' => 'The gender must be one of the following: M, F, O.',

    //     'date_of_birth.required' => 'The date of birth field is required.',
    //     'date_of_birth.date' => 'The date of birth must be a valid date.',

    //     'email.required' => 'The email field is required.',
    //     'email.email' => 'The email must be a valid email address.',
    //     'email.max' => 'The email may not be greater than :max characters.',

    //     'phone.required' => 'The phone field is required.',
    //     'phone.string' => 'The phone must be a string.',
    //     'phone.max' => 'The phone may not be greater than :max characters.',

    //     'address1.required' => 'The address line 1 field is required.',
    //     'address1.string' => 'The address line 1 must be a string.',
    //     'address1.max' => 'The address line 1 may not be greater than :max characters.',

    //     'address2.required' => 'The address line 2 field is required.',
    //     'address2.string' => 'The address line 2 must be a string.',
    //     'address2.max' => 'The address line 2 may not be greater than :max characters.',

    //     'country.required' => 'The country field is required.',
    //     'country.string' => 'The country must be a string.',
    //     'country.max' => 'The country may not be greater than :max characters.',

    //     'state.required' => 'The state field is required.',
    //     'state.string' => 'The state must be a string.',
    //     'state.max' => 'The state may not be greater than :max characters.',

    //     'city.required' => 'The city field is required.',
    //     'city.string' => 'The city must be a string.',
    //     'city.max' => 'The city may not be greater than :max characters.',

    //     'pincode.required' => 'The pincode field is required.',
    //     'pincode.string' => 'The pincode must be a string.',
    //     'pincode.max' => 'The pincode may not be greater than :max characters.',

    //     'role.required' => 'The role field is required.',

    //     'profile_photo.image' => 'The profile photo must be an image file.',
    //     'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
    //     'profile_photo.max' => 'The profile photo may not be greater than :max kilobytes.',
    // ];
    //}
}
