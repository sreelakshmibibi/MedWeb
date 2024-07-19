<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToothExaminationRequest extends FormRequest
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
            'tooth_score_id' => 'required',
            'chief_complaint' => 'required|string',
            'hpi' => 'required|string',
            'dental_examination' => 'required|string',
            'disease_id' => 'required',
            'diagnosis'=> 'required|string',
            'treatment_id' => 'required',
            'x-ray' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lingual_condn' => 'nullable',
            'labial_condn'=> 'nullable',
            'occulusal_condn' => 'nullable',
            'distal_condn' => 'nullable',
            'mesial_condn'=> 'nullable',
            'palatal_condn' => 'nullable',
            'buccal_condn' => 'nullable',
            'treatment_status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tooth_score_id.required' => 'The first name field is required.',
            
            'chief_complaint.required' => 'The chief complaint field is required.',
            'chief_complaint.string' => 'The chief complaint must be a string.',
            
            'hpi.required' => 'HPI is required',
            'hpi.string' => 'The HPI must be a string.',
            
            'dental_examination.required' => 'Dental examination is required',
            'dental_examination.string' => 'The dental examination must be a string.',

            'disease_id.required' => 'Disease is required',
            
            'diagnosis.required'=> 'Diagnosis is required',
            'diagnosis.string' => 'The diagnosis must be a string.',
           
            'treatment_id.required' => 'Treatment is required',
            
            'x-ray.image' => 'The x-ray must be an image file.',
            'x-ray.mimes' => 'The x-ray must be a file of type: jpeg, png, jpg, gif.',
            'x-ray.max' => 'The x-ray may not be greater than :max kilobytes.',
            
            'treatment_status.required' => 'Treatment status is required',
        ];
    }
}