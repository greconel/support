<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Notes extends Component
{
    public Quotation $quotation;

    #[On('refreshQuotation')]
    public function refreshQuotation(): void
    {
        $this->quotation->refresh();
    }

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.notes');
    }
}
