<?php

namespace App\Http\Livewire\Ampp;

use App\Models\HelpMessage;
use App\Models\User;
use App\Notifications\Admin\HelpNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class HelpCenter extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $message = '';
    public $images = [];

    public function send()
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'images.*' => ['nullable', 'image', 'max:2048']
        ]);

        $helpMessage = HelpMessage::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'message' => $this->message
        ]);

        foreach ($this->images as $image){
            $helpMessage
                ->addMedia($image->getRealPath())
                ->toMediaCollection('images', 'help')
            ;
        }

        User::whereRelation('roles', 'name', '=', 'super admin')
            ->notify(new HelpNotification($helpMessage));

        session()->flash('send_success', __('Thank you for using our help center. We will contact you soon!'));

        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.ampp.help-center');
    }
}
