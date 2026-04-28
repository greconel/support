<?php

namespace App\Http\Requests\Ampp\GdprMessages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGdprMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'when' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'what' => ['required', 'string', 'max:255'],
            'amount_of_details' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'consequences' => ['required', 'string', 'max:255'],
            'measures' => ['required', 'string', 'max:255'],
            'notification_requirements' => ['required', 'string', 'max:255'],
        ];
    }
}
