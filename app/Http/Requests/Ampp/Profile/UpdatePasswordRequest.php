<?php

namespace App\Http\Requests\Ampp\Profile;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    use PasswordValidationRules;

    public function rules(): array
    {
        return [
            'current_password' => 'current_password:web',
            'password' => $this->passwordRules(),
        ];
    }
}
