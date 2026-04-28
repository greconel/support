<?php

namespace App\Http\Livewire\Ampp\Quotations\Show;

use App\Models\Quotation;
use App\Traits\MediaTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Files extends Component
{
    use WithFileUploads;
    use MediaTrait;

    public Quotation $quotation;
    public $files = [];

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function save()
    {
        $this->validate([
            'files' => ['required'],
            'files.*' => ['file', 'max:15000']
        ]);

        foreach ($this->files as $file){
            $this->quotation
                ->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files', 'private')
            ;
        }

        $this->reset('files');

        $this->quotation->refresh();
    }

    public function download($id): BinaryFileResponse
    {
        return $this->downloadMedia($this->quotation->getMedia('files')->firstWhere('id', $id));
    }

    public function delete($id)
    {
        $this->quotation->getMedia('files')->find($id)?->delete();

        $this->quotation->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.quotations.show.files');
    }
}
