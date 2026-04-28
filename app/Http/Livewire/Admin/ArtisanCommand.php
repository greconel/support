<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class ArtisanCommand extends Component
{
    public string $command = "";
    public string $output = "";

    public function run()
    {
        $this->validate([
            'command' => ['required', 'string']
        ]);

        try {
            define('STDIN',fopen("php://stdin","r"));
            Artisan::call($this->command);
            $this->output = Artisan::output();
        } catch (\Exception $e){
            $this->output = $e;
            session()->flash('error_artisan', __('artisan.error'));
        }

        $this->reset('command');
    }

    public function render(): View
    {
        return view('livewire.admin.artisan');
    }
}
