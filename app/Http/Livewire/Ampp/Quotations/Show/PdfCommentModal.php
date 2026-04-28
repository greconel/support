<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PdfCommentModal extends Component
{
    public Quotation $quotation;

    protected $rules = [
        'quotation.pdf_comment' => ['nullable', 'string']
    ];

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function updatePdfComment()
    {
        $this->validate();
        $this->quotation->save();

        $this->dispatch('close')->self();
        $this->dispatch('refreshQuill')->self();
        $this->dispatch('refreshQuotation')->to('ampp.quotations.show.pdf-comment');

    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.pdf-comment-modal');
    }
}
