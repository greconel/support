<?php

namespace App\Http\Requests\Ampp\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadClearfactsBulkExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        $expenses = Expense::notUploadedToClearfacts()->pluck('id');

        return [
            'expenses' => ['array', 'required'],
            'expenses.*' => [Rule::in($expenses)]
        ];
    }
}
