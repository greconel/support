<?php

namespace App\Http\Requests\Ampp\TimeRegistrations;

use App\Models\TimeRegistration;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportTimeRegistrationsRequest extends FormRequest
{
    public function rules(): array
    {
        if (auth()->user()->can('viewOtherUsers', TimeRegistration::class)){
            $users = User::pluck('id');
        } else {
            $users = [auth()->id()];
        }

        return [
            'user' => ['nullable', 'required_if:project,null', Rule::in($users)],
            'project' => ['nullable', 'required_if:user,null', Rule::in(auth()->user()->projects->pluck('id'))],
        ];
    }
}
