<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;

    public Quotation $quotation;
    public array $clients = [];
    public string $expirationDate = '';
    public string $customCreatedAt = '';

    protected $rules = [
        'quotation.client_id' => ['required', 'int'],
        'quotation.number' => ['required', 'int'],
        'expirationDate' => ['required', 'date'],
        'customCreatedAt' => ['required', 'date'],
    ];

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
        $this->clients = Client::all()->pluck('full_name_with_company', 'id')->toArray();

        $this->expirationDate = $quotation->expiration_date->format('Y-m-d');
        $this->customCreatedAt = $quotation->custom_created_at->format('Y-m-d');
    }

    public function edit()
    {
        $this->authorize('update', $this->quotation);

        $this->validate();

        $this->validate([
            'quotation.number' => [
                Rule::unique('quotations', 'number')->where(function ($q){
                    return $q->whereYear('custom_created_at', $this->quotation->custom_created_at);
                })->ignoreModel($this->quotation)
            ]
        ]);

        $this->quotation->expiration_date = $this->expirationDate;
        $this->quotation->custom_created_at = $this->customCreatedAt;
        $this->quotation->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuotation')->to('ampp.quotations.show.details');

    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.edit-modal');
    }
}
