<?php

namespace App\Http\Livewire\Ampp\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Info extends Component
{
    use WithFileUploads;

    public string $name;
    public string $email;
    public $photo;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function edit()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignoreModel(Auth::user())],
            'photo' => ['nullable', 'image', 'max:2048']
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'email' => $this->email
        ]);

        if ($this->photo) {
            auth()
                ->user()
                ->clearMediaCollection('profile_photo')
            ;

            auth()
                ->user()
                ->addMedia($this->photo->getRealPath())
                ->toMediaCollection('profile_photo', 'private')
            ;
        }

        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->photo = null;

        session()->flash('success', __('Successfully edited profile info'));
    }

    public function render(): View
    {
        return view('livewire.ampp.profile.info');
    }
}
