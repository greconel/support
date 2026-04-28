<?php

namespace App\Http\Requests\Ampp\Clients;

use App\Enums\ClientType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(ClientType::class)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'vat' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'invoice_note' => ['nullable', 'string'],
            'peppol_only' => ['nullable', 'in:1'],
            'get_coordinates' => ['nullable', 'in:1'],
        ];
    }
}
