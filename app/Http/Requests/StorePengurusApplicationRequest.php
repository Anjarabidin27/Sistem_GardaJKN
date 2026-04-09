<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengurusApplicationRequest extends FormRequest
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
            'is_interested_pengurus' => 'required|boolean',
            'interest_pil' => 'nullable|boolean',
            'interest_keliling' => 'nullable|boolean',
            'has_org_experience' => 'required_if:is_interested_pengurus,1|boolean',
            'org_name' => 'required_if:has_org_experience,1|nullable|string|max:255',
            'org_position' => 'required_if:has_org_experience,1|nullable|string|max:255',
            'org_duration_months' => 'required_if:has_org_experience,1|nullable|integer|min:1',
            'org_description' => 'required_if:has_org_experience,1|nullable|string',
            'org_certificate' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ];
    }
}
