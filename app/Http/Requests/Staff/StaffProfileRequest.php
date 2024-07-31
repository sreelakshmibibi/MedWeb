<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffProfileRequest extends FormRequest
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:M,F,O',
            'date_of_birth' => 'required|date',
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique('Users')->ignore($this->edit_user_id),
            ],
            'aadhaar_no' => [
                'required',
                'string',
                'max:12',
                Rule::unique('staff_profiles')->ignore($this->edit_user_id, 'user_id'),
            ],
            'phone' => 'required|string|max:20',
            'address1' => 'required|string|max:255',
            'address2' => 'required|string|max:255',
            'country_id' => 'required|string|max:255',
            'state_id' => 'required|string|max:255',
            'city_id' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'role' => 'required',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qualification' => 'required|string|max:255',
            'years_of_experience' => 'required|string|max:255',
            'department_id' => 'required',
            'designation' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
            'date_of_relieving' => 'nullable|date',
            'license_number' =>  [
                'nullable',
                'string',
                Rule::unique('staff_profiles')->ignore($this->edit_user_id, 'user_id'),
            ],
            'specialization' =>  'nullable|string|max:255', 
            'subspecialty' => 'nullable|string|max:255', 
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
            'firstname.required' => 'The first name field is required.',
            'firstname.string' => 'The first name must be a string.',
            'firstname.max' => 'The first name may not be greater than :max characters.',
            
            'lastname.required' => 'The last name field is required.',
            'lastname.string' => 'The last name must be a string.',
            'lastname.max' => 'The last name may not be greater than :max characters.',
            
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'The gender must be one of the following: M, F, O.',
            
            'date_of_birth.required' => 'The date of birth field is required.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'Email already exists.',

            'aadhaar_no.required' => 'The aadhaar no field is required.',
            'aadhaar_no.email' => 'The aadhaar no must be a valid email address.',
            'aadhaar_no.max' => 'The aadhaar no may not be greater than :max characters.',
            'aadhaar_no.unique' => 'Aadhaar no has already been taken. Try another.',

            'phone.required' => 'The phone field is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.max' => 'The phone may not be greater than :max characters.',
            
            'address1.required' => 'The address line 1 field is required.',
            'address1.string' => 'The address line 1 must be a string.',
            'address1.max' => 'The address line 1 may not be greater than :max characters.',
            
            'address2.required' => 'The address line 2 field is required.',
            'address2.string' => 'The address line 2 must be a string.',
            'address2.max' => 'The address line 2 may not be greater than :max characters.',
            
            'country.required' => 'The country field is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than :max characters.',
            
            'state.required' => 'The state field is required.',
            'state.string' => 'The state must be a string.',
            'state.max' => 'The state may not be greater than :max characters.',
            
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than :max characters.',
            
            'pincode.required' => 'The pincode field is required.',
            'pincode.string' => 'The pincode must be a string.',
            'pincode.max' => 'The pincode may not be greater than :max characters.',
            
            'role.required' => 'The role field is required.',
            
            'profile_photo.image' => 'The profile photo must be an image file.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'The profile photo may not be greater than :max kilobytes.',

            'qualification.required' => 'The qualification field is required.',
            'qualification.string' => 'The qualification must be a string.',
            'qualification.max' => 'The qualification may not be greater than :max characters.',

            'years_of_experience.required' => 'The experience field is required.',
            'years_of_experience.string' => 'The experience must be a string.',
            'years_of_experience.max' => 'The experience may not be greater than :max characters.',

            'department_id.required' => 'The department field is required.',

            'designation.required' => 'The designation field is required.',
            'designation.string' => 'The designation must be a string.',
            'designation.max' => 'The designation may not be greater than :max characters.',

            'date_of_joining.required' => 'The date of joining field is required.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',

            'date_of_relieving.date' => 'The date of relieving must be a valid date.',

            'license_number.string' => 'The experience must be a string.',
            'license_number.unique' => 'Licence number already exists.',

            'specialization.string' => 'The specialization must be a string.',
            'specialization.max' => 'The specialization may not be greater than :max characters.',

            'subspecialty.string' => 'The subspecialty must be a string.',
            'subspecialty.max' => 'The subspecialty may not be greater than :max characters.',
        ];
    }
}
