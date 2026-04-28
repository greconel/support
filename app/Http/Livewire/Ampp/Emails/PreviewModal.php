<?php

namespace App\Http\Livewire\Ampp\Emails;

use App\Models\Email;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewModal extends Component
{
    public Model $emailModel;
    public ?Email $previewEmail = null;

    public function mount(Model $emailModel)
    {
        $this->emailModel = $emailModel;
    }

    #[On('previewEmail')]
    public function preview($id)
    {
        $this->reset('previewEmail');

        $this->previewEmail = $this->emailModel->emails->find($id);

        $this->dispatch('open')->self();
    }

    public function render(): View
    {
        return view('livewire.ampp.emails.preview-modal');
    }
}
