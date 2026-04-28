<?php

namespace App\Http\Requests\Ampp\ProjectActivities;

use Illuminate\Foundation\Http\FormRequest;

class ProjectActivityFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'budget_in_hours' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean']
        ];
    }
}
