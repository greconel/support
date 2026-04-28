<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore(request()->route('user'))],
            'roles' => ['nullable', 'array', Rule::notIn(Role::firstWhere('name', 'super admin')->id)],
            'password_resend' => ['nullable']
        ];
    }
}
