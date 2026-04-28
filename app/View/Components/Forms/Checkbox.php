<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;

class Checkbox extends Input
{
    public bool $checked;

    public function __construct(
        string $name,
        string $id = null,
        bool $checked = false,
        ?string $value = '',
        ?string $errorFor = null,
    ) {
        parent::__construct($name, $id, 'checkbox', $value, $errorFor);

        $this->checked = (bool) old($name, $checked);
    }

    public function render(): View
    {
        return view('components.forms.checkbox');
    }
}
