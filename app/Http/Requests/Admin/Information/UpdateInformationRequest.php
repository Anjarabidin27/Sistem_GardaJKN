<?php

namespace App\Http\Requests\Admin\Information;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'string|max:255',
            'content' => 'nullable|string',
            'type' => 'in:text,image,pdf',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'is_active' => 'boolean',
        ];
    }
}
