<?php

namespace App\Http\Requests\Ampp\Projects;

use App\Enums\ProjectCategory;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'budget_money' => ['nullable', 'numeric'],
            'budget_hours' => ['nullable', 'int'],
            'client_id' => ['nullable', 'int', Rule::in(Client::pluck('id'))],
            'category' => ['required', new Enum(ProjectCategory::class)],
            'assignee' => ['nullable', 'string'],
            'users' => ['required', 'array', 'min:1'],
            'users.*' => ['required', 'int', Rule::in(User::pluck('id'))],
            'description' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'color' => ['required', 'string', 'max:255'],
            'is_general' => ['nullable']
        ];
    }
}
