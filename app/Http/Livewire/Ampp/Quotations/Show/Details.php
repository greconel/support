<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Enums\ClientType;
use App\Enums\QuotationStatus;
use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;
use Livewire\Component;

class Details extends Component
{
    public Quotation $quotation;
    public string $status = '';
    public ?string $acceptedAt = null;

    #[On('refreshQuotation')]
    public function refreshQuotation(): void
    {
        $this->quotation->refresh();
    }

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
        $this->acceptedAt = $this->quotation->accepted_at?->format('Y-m-d');
    }

    public function updateStatus()
    {
        $this->validate([
            'status' => ['required', new Enum(QuotationStatus::class)]
        ]);

        $this->quotation->status = $this->status;

        $this->quotation->accepted_at = $this->quotation->status == QuotationStatus::Accepted
            ? $this->quotation->accepted_at ?? now()
            : null;

        $this->quotation->save();

        $this->acceptedAt = $this->quotation->accepted_at?->format('Y-m-d');

        $this->reset('status');

        if ($this->quotation->status != QuotationStatus::Accepted){
            return;
        }

        if ($this->quotation->client->type != ClientType::Lead){
            return;
        }

        $this->quotation->client->update(['type' => ClientType::Client]);
    }

    public function updatedAcceptedAt()
    {
        $this->validate([
            'acceptedAt' => ['nullable', 'date']
        ]);

        $this->quotation->update(['accepted_at' => empty($this->acceptedAt) ? null : $this->acceptedAt]);
    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.details');
    }
}
