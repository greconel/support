<?php

namespace App\Http\Livewire\Ampp\Emails;

use App\Models\Email;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ListForModel extends Component
{
    public Model $emailModel;
    public Collection $emails;
    public ?string $createModal = null;

    public function mount(Model $emailModel, string $createModal = null)
    {
        $this->emailModel = $emailModel;
        $this->emails = $emailModel->emails;
        $this->createModal = $createModal;
    }

    #[On('refreshMails')]
    public function refreshMails()
    {
        $this->emailModel->refresh();
        $this->emails = $this->emailModel->emails;
    }

    public function render(): View
    {
        return view('livewire.ampp.emails.list-for-model');
    }
}
