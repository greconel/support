<?php

namespace App\Http\Requests\Admin\Connections;

use Illuminate\Foundation\Http\FormRequest;

class StoreConnectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'icon' => ['required', 'image', 'max:255']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}