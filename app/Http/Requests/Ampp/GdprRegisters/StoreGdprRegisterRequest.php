<?php

namespace App\Http\Requests\Ampp\GdprRegisters;

use Illuminate\Foundation\Http\FormRequest;

class StoreGdprRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'processing_activity' => ['string', 'required'],
            'processing_activity_input' => ['string', 'required'],
            'processing_purpose' => ['string', 'required'],
            'processing_purpose_input' => ['string', 'required'],
            'subject_category' => ['string', 'required'],
            'subject_category_input' => ['string', 'required'],
            'data_type' => ['string', 'required'],
            'data_type_input' => ['string', 'required'],
            'receiver_type' => ['string', 'required'],
            'retention_period' => ['string', 'required'],
            'legal_basis' => ['string', 'required'],
            'legal_basis_input' => ['string', 'required'],
            'transfers_to' => ['string', 'required'],
            'nature_transfers' => ['string', 'required'],
            'nature_transfers_input' => ['string', 'required'],
            'technical_measures' => ['string', 'required'],
            'database' => ['string', 'required'],
            'access' => ['string', 'required'],
        ];
    }
}
