<?php

namespace App\Http\Requests\Ampp\ClientContacts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
