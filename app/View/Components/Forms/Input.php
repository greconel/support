<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public string $name;
    public ?string $id;
    public string $type;
    public ?string $value;
    public ?string $errorFor;

    public function __construct(
        string $name,
        string $id = null,
        string $type = 'text',
        ?string $value = '',
        ?string $errorFor = null,
    ) {
        $this->name = $name;
        $this->id = $id;
        $this->type = $type;
        $this->value = old($name, $value ?? '');
        $this->errorFor = $errorFor ?? $name;
    }

    public function render(): View
    {
        return view('components.forms.input');
    }
}
