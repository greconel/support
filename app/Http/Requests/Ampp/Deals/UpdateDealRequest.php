<?php

namespace App\Http\Requests\Ampp\Deals;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDealRequest extends FormRequest
{
    public function rules(): array
    {
        $leads = Client::leads()->pluck('id')->push('new_lead');

        return [
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', Rule::in($leads)],
            'due_date' => ['nullable', 'date'],
            'chance_of_success' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'expected_revenue' => ['nullable', 'numeric'],
            'expected_start_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'lead_first_name' => ['nullable', 'string', 'max:255', 'required_if:client_id,new_lead'],
            'lead_last_name' => ['nullable', 'string', 'max:255', 'required_if:client_id,new_lead'],
            'lead_company' => ['nullable', 'string', 'max:255'],
            'lead_vat' => ['nullable', 'string', 'max:255'],
            'lead_email' => ['nullable', 'email', 'max:255'],
            'lead_phone' => ['nullable', 'string', 'max:255'],
        ];
    }
}
