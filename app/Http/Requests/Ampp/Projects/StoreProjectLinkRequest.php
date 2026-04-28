<?php

namespace App\Http\Requests\Ampp\Projects;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'href' => ['required', 'string', 'url'],
        ];
    }
}
