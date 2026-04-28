<?php

namespace App\Http\Livewire\Ampp\Profile;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Password extends Component
{
    use PasswordValidationRules;

    public string $oldPassword = "";
    public string $password = "";
    public string $password_confirmation = "";

    public function edit()
    {
        $this->validate([
            'password' => $this->passwordRules()
        ]);

        if (!Hash::check($this->oldPassword, auth()->user()->password)){
            session()->flash('error', __('Current password is incorrect!'));
            $this->reset('oldPassword');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->password)
        ]);

        session()->flash('success', __('Successfully updated password'));
        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.ampp.profile.password');
    }
}
