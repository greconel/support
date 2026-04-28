<?php

namespace App\Http\Requests\Ampp\Invoices;

use App\Enums\InvoiceType;
use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', new Enum(InvoiceType::class)],
            'client_id' => ['required', 'int', Rule::in(Client::all()->pluck('id'))],
            'expiration_date' => ['required', 'date_format:d/m/Y'],
            'custom_created_at' => ['required', 'date_format:d/m/Y'],
            'invoice_category_id' => ['nullable', 'integer', 'exists:invoice_categories,id']
        ];
    }
}
