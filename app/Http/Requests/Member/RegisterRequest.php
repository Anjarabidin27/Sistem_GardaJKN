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
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(\+62|08)[0-9]{8,15}$/'],
            'gender' => 'required|in:L,P',
            'education' => 'required|in:SD,SMP,SMA,Diploma,S1/D4,S2',
            'occupation' => 'required|in:Petani,Pedagang,Nelayan,Wiraswasta,Karyawan,PNS,TNI/POLRI,Lainnya',
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
            'address_detail' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }
}
