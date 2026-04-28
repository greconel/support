<?php

namespace App\Http\Requests\Api\V1\Invoices;

use App\Enums\InvoiceType;
use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class IndexInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => ['required', Rule::in(Client::pluck('id'))],
            'type' => ['nullable', new Enum(InvoiceType::class)],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'till' => ['required_with:from', 'date_format:Y-m-d', 'after_or_equal:from']
        ];
    }
}
