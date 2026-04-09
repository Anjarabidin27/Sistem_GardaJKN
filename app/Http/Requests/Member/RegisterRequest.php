<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|numeric|digits:16|unique:members,nik',
            'jkn_number' => 'nullable|string|min:10|max:16',
            'name' => 'required|string|max:255',
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'education' => 'required|in:SD,SMP,SMA,Diploma,S1/D4,S2,S3',
            'occupation' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => [
                'required', 
                'exists:cities,id', 
                new \App\Rules\ValidRegionHierarchy($this->province_id, 'city')
            ],
            'district_id' => [
                'required', 
                'exists:districts,id', 
                new \App\Rules\ValidRegionHierarchy($this->city_id, 'district')
            ],
            'dom_province_id' => 'required|exists:provinces,id',
            'dom_city_id' => [
                'required', 
                'exists:cities,id', 
                new \App\Rules\ValidRegionHierarchy($this->dom_province_id, 'city')
            ],
            'dom_district_id' => [
                'required', 
                'exists:districts,id', 
                new \App\Rules\ValidRegionHierarchy($this->dom_city_id, 'district')
            ],
            'dom_address_detail' => 'required|string',
            'address_detail' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            
            // Pengurus Interest Fields
            'is_interested_pengurus' => 'nullable|boolean',
            'interest_pil'           => 'nullable|boolean',
            'interest_keliling'      => 'nullable|boolean',
            'has_org_experience'     => 'nullable|boolean',
            'org_name'               => 'nullable|string|max:255',
            'pengurus_reason'        => 'nullable|string|max:1000',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_interested_pengurus' => filter_var($this->is_interested_pengurus, FILTER_VALIDATE_BOOLEAN),
            'interest_pil'           => filter_var($this->interest_pil, FILTER_VALIDATE_BOOLEAN),
            'interest_keliling'      => filter_var($this->interest_keliling, FILTER_VALIDATE_BOOLEAN),
            'has_org_experience'     => filter_var($this->has_org_experience, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
