<?php

namespace App\Http\Requests\Ampp\GdprAudits;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGdprAuditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'what' => ['required', 'string'],
            'when' => ['required', 'string'],
            'why' => ['required', 'string'],
        ];
    }
}
