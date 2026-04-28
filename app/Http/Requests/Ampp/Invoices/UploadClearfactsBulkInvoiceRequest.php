<?php

namespace App\Http\Requests\Ampp\Invoices;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadClearfactsBulkInvoiceRequest extends FormRequest
{
    public function rules(): array
    {
        $invoices = Invoice::notUploadedToClearfacts()->pluck('id');

        return [
            'invoices' => ['array', 'required'],
            'invoices.*' => [Rule::in($invoices)]
        ];
    }
}
