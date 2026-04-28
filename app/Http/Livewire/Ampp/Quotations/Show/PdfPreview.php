<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfPreview extends Component
{
    use AuthorizesRequests;

    public Quotation $quotation;
    public string $pdf = '';

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function generate()
    {
        $this->authorize('view', $this->quotation);

        $this->quotation->refresh();

        $this->pdf = $this->quotation->generatePdf(null, true);
    }

    public function download(): BinaryFileResponse
    {
        $this->authorize('view', $this->quotation);

        Storage::put('temp/' . $this->quotation->file_name, base64_decode($this->pdf));

        return response()->download(storage_path('app/temp/' .  $this->quotation->file_name))->deleteFileAfterSend();
    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.pdf-preview');
    }
}
