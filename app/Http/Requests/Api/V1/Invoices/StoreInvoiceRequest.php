<?php

namespace App\Http\Requests\Api\V1\Invoices;

use App\Enums\InvoiceStatus;
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
            'client_id' => ['required', 'int', Rule::in(Client::pluck('id'))],
            'status' => ['required', new Enum(InvoiceStatus::class)],
            'notes' => ['nullable', 'string'],
            'pdf_comment' => ['nullable', 'string'],
            'expiration_date' => ['required', 'date_format:Y-m-d'],
            'custom_created_at' => ['required', 'date_format:Y-m-d'],
            'billing_lines' => ['nullable', 'array'],
            'billing_lines.*.type' => ['required', 'in:header,text'],
            'billing_lines.*.order' => ['required', 'integer'],
            'billing_lines.*.text' => ['required', 'string', 'max:255'],
            'billing_lines.*.price' => ['nullable', 'numeric'],
            'billing_lines.*.subtotal' => ['nullable', 'numeric'],
            'billing_lines.*.amount' => ['nullable', 'numeric'],
            'billing_lines.*.vat' => ['nullable', 'numeric'],
            'billing_lines.*.discount' => ['nullable', 'numeric'],
            'billing_lines.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
