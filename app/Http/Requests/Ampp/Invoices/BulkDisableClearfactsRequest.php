<?php

namespace App\Http\Requests\Ampp\Invoices;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkDisableClearfactsRequest extends FormRequest
{
    public function rules(): array
    {
        $invoices = Invoice::disabledForClearfacts(false)->pluck('id');

        return [
            'invoices' => ['array', 'nullable'],
            'invoices.*' => [Rule::in($invoices)]
        ];
    }
}
