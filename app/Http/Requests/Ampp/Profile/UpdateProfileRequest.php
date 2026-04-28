<?php

namespace App\Http\Requests\Ampp\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignoreModel(auth()->user())],
            'avatar' => ['nullable', 'image', 'max:2000']
        ];
    }
}
