<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Quill extends Component
{
    public function __construct(
        public string $name,
        public ?string $value = '',
        public bool $livewire = false
    )
    {
        $this->value = old($name, $value ?? '');
    }

    public function render(): View
    {
        return view('components.forms.quill');
    }
}
