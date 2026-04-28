<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfPreview extends Component
{
    use AuthorizesRequests;

    public Invoice $invoice;
    public string $pdf = '';

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function generate()
    {
        $this->authorize('view', $this->invoice);

        $this->invoice->refresh();

        $this->pdf = $this->invoice->generatePdf(null, true);
    }

    public function download(): BinaryFileResponse
    {
        $this->authorize('view', $this->invoice);

        Storage::put('temp/' . $this->invoice->file_name, base64_decode($this->pdf));

        return response()->download(storage_path('app/temp/' .  $this->invoice->file_name))->deleteFileAfterSend();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.pdf-preview');
    }
}
