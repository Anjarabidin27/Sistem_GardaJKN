<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|numeric|digits:16',
            'password' => 'required|string',
        ];
    }
}
