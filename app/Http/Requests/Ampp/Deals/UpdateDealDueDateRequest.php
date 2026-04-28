<?php

namespace App\Http\Requests\Ampp\Deals;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealDueDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'due_date' => ['nullable', 'date']
        ];
    }
}
