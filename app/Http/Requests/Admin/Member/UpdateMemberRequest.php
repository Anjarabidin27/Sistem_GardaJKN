<?php

namespace App\Http\Requests\Admin\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'phone' => ['sometimes', 'string', 'regex:/^(\+62|08)[0-9]{8,15}$/'],
            'gender' => 'sometimes|in:L,P',
            'education' => 'sometimes|in:SD,SMP,SMA,Diploma,S1/D4,S2',
            'occupation' => 'sometimes|in:Petani,Pedagang,Nelayan,Wiraswasta,Karyawan,PNS,TNI/POLRI,Lainnya',
            'province_id' => 'sometimes|exists:provinces,id',
            'city_id' => 'sometimes|exists:cities,id',
            'district_id' => 'sometimes|exists:districts,id',
            'address_detail' => 'sometimes|string',
            'status' => 'sometimes|string', 
        ];
    }
}
