<?php

namespace App\Http\Livewire\Ampp\Suppliers;

use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

class Vat extends Component
{
    public ?Supplier $supplier = null;
    public ?string $vat = null;
    public array $supplierVats = [];

    public function mount()
    {
        $this->supplierVats = Supplier::all()
            ->except([$this->supplier?->id])
            ->each(fn (Supplier $supplier) => $supplier->vat = (string) Str::of($supplier->vat)->lower()->remove(' '))
            ->pluck('vat', 'id')
            ->toArray();
    }

    public function updatedVat($value)
    {
        $value = (string) Str::of($value)->lower()->remove(' ');

        if (empty($value)){
            $this->resetErrorBag('vat');
            return;
        }

        if (collect($this->supplierVats)->search($value) != false){
            $this->addError('vat', __('This VAT number is already in use'));
        } else {
            $this->resetErrorBag('vat');
        }
    }

    public function render(): View
    {
        return view('livewire.ampp.suppliers.vat');
    }
}
