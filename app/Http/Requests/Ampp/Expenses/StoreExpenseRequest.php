<?php

namespace App\Http\Requests\Ampp\Expenses;

use App\Enums\VariousTransactionCategory;
use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'supplier_id' => ['required', 'int', Rule::in(Supplier::pluck('id'))],
            'invoice_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'invoice_ogm' => ['nullable', 'string', 'size:20'],
            'amount_excluding_vat' => ['nullable', 'numeric'],
            'amount_including_vat' => ['nullable', 'numeric'],
            'amount_vat' => ['nullable', 'numeric'],
            'amount_vat_percentage' => ['nullable', 'numeric'],
            'comment' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf'],
            'various_transaction_category' => ['required', new Enum(VariousTransactionCategory::class)],
            'invoice_category_id' => ['nullable', 'integer', 'exists:invoice_categories,id']
        ];
    }
}
