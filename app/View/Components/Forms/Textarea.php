<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public string $name;
    public ?string $id;
    public int $rows;

    public function __construct(string $name, string $id = null, int $rows = 3)
    {
        $this->name = $name;
        $this->id = $id;
        $this->rows = $rows;
    }

    public function render(): View
    {
        return view('components.forms.textarea');
    }
}
