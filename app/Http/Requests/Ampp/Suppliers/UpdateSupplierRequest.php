<?php

namespace App\Http\Requests\Ampp\Suppliers;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class UpdateSupplierRequest extends FormRequest
{
    protected function withValidator(Validator $validator)
    {
        /** @var Supplier $currentSupplier */
        $currentSupplier = request()->route('supplier');

        $vats = Supplier::all()
            ->except([$currentSupplier->id])
            ->each(fn (Supplier $supplier) => $supplier->vat = (string) Str::of($supplier->vat)->lower()->remove(' '))
            ->pluck('vat')
        ;

        $validator->after(function (Validator $validator) use ($vats){
            $vat = (string) Str::of($this->input('vat'))->lower()->remove(' ');

            if (empty($vat)){
                return;
            }

            if ($vats->search($vat) !== false){
                $validator->errors()->add('vat', __('This VAT number is already in use'));
            }
        });
    }

    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'vat' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_general' => ['nullable']
        ];
    }
}
