<?php

namespace App\Http\Livewire\Ampp\Invoices\Show;

use App\Models\Invoice;
use App\Traits\MediaTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Files extends Component
{
    use WithFileUploads;
    use MediaTrait;

    public Invoice $invoice;
    public $files = [];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function save()
    {
        $this->validate([
            'files' => ['required'],
            'files.*' => ['file', 'max:15000']
        ]);

        foreach ($this->files as $file){
            $this->invoice
                ->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files', 'private')
            ;
        }

        $this->reset('files');

        $this->invoice->refresh();
    }

    public function download($id): BinaryFileResponse
    {
        return $this->downloadMedia($this->invoice->getMedia('files')->firstWhere('id', $id));
    }

    public function delete($id)
    {
        $this->invoice->getMedia('files')->find($id)?->delete();

        $this->invoice->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.invoices.show.files');
    }
}
