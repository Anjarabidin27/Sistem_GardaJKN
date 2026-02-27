<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'jkn_number' => 'nullable|numeric|digits_between:10,15',
            'phone' => ['required', 'string', 'regex:/^(\+?62|08)[0-9]{8,15}$/'],
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'education' => 'required|in:SD,SMP,SMA,Diploma,S1/D4,S2',
            'occupation' => 'required|string',
            'address_detail' => 'required|string',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => [
                'nullable', 
                'exists:cities,id', 
                function ($attribute, $value, $fail) {
                    if ($this->province_id && $value) {
                        $rule = new \App\Rules\ValidRegionHierarchy($this->province_id, 'city');
                        $rule->validate($attribute, $value, $fail);
                    }
                }
            ],
            'district_id' => [
                'nullable', 
                'exists:districts,id', 
                function ($attribute, $value, $fail) {
                    if ($this->city_id && $value) {
                        $rule = new \App\Rules\ValidRegionHierarchy($this->city_id, 'district');
                        $rule->validate($attribute, $value, $fail);
                    }
                }
            ],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            
            // Pengurus Application Fields
            'is_interested_pengurus' => 'nullable|boolean',
            'has_org_experience' => 'nullable|boolean',
            'org_count' => 'nullable|integer',
            'org_name' => 'nullable|string|max:255',
            'org_position' => 'nullable|string|max:255',
            'org_duration_months' => 'nullable|integer',
            'org_description' => 'nullable|string',
            'pengurus_reason' => 'nullable|string',
            'status_pengurus' => 'nullable|string'
        ];
    }
}
