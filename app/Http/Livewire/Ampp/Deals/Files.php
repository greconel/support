<?php

namespace App\Http\Livewire\Ampp\Deals;

use App\Models\Deal;
use App\Traits\MediaTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Files extends Component
{
    use WithFileUploads;
    use MediaTrait;

    public Deal $deal;
    public $files = [];

    public function mount(Deal $deal)
    {
        $this->deal = $deal;
    }

    public function save()
    {
        $this->validate([
            'files' => ['required'],
            'files.*' => ['file', 'max:15000']
        ]);

        foreach ($this->files as $file){
            $this->deal
                ->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection('files', 'private')
            ;
        }

        $this->reset('files');

        $this->deal->refresh();
    }

    public function download($id): BinaryFileResponse
    {
        return $this->downloadMedia($this->deal->getMedia('files')->firstWhere('id', $id));
    }

    public function delete($id)
    {
        $this->deal->getMedia('files')->find($id)?->delete();

        $this->deal->refresh();
    }

    public function render(): View
    {
        return view('livewire.ampp.deals.files');
    }
}
