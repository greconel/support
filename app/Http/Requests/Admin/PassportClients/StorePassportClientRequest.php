<?php

namespace App\Http\Requests\Admin\PassportClients;

use Illuminate\Foundation\Http\FormRequest;

class StorePassportClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'redirect' => ['nullable', 'string', 'max:255']
        ];
    }
}
