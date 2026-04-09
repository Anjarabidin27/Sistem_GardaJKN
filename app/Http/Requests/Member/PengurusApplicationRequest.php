<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class PengurusApplicationRequest extends FormRequest
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
            'is_interested_pengurus' => 'required|boolean',
            'interest_pil' => 'nullable|boolean',
            'interest_keliling' => 'nullable|boolean',
            'has_org_experience' => 'required_if:is_interested_pengurus,true|boolean',
            'org_count' => 'required_if:has_org_experience,true|integer|min:1',
            'org_name' => 'required_if:has_org_experience,true|string',
            'pengurus_reason' => 'required_if:has_org_experience,true|string',
        ];
    }
}
