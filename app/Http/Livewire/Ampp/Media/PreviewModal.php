<?php

namespace App\Http\Livewire\Ampp\Media;

use App\Traits\MediaTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PreviewModal extends Component
{
    use MediaTrait;

    public Media $media;

    #[On('openMediaPreviewModal')]
    public function openMediaPreviewModal($id)
    {
        $this->media = Media::find($id);
        $this->canInteractWithMedia($this->media);

        if (!Str::contains($this->media->mime_type, ['image', 'pdf'])){
            return $this->downloadMedia($this->media);
        }

        $this->dispatch('toggle')->self();
    }

    public function download(): BinaryFileResponse
    {
        return $this->downloadMedia($this->media);
    }

    public function render(): View
    {
        return view('livewire.ampp.media.preview-modal');
    }
}
