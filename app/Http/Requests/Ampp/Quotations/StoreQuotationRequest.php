<?php

namespace App\Http\Requests\Ampp\Quotations;

use App\Models\Client;
use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class StoreQuotationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'int', Rule::in(Client::all()->pluck('id'))],
            'expiration_date' => ['required', 'date_format:d/m/Y'],
            'custom_created_at' => ['required', 'date_format:d/m/Y'],
            'number' => [
                'required',
                'int',
                Rule::unique('quotations')->where(function ($q){
                    return $q->whereYear('custom_created_at', Carbon::createFromFormat('d/m/Y', request()->input('custom_created_at')));
                })
            ],
            'deal_id' => ['nullable', Rule::in(Deal::pluck('id'))]
        ];
    }
}
