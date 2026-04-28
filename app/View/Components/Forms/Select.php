<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Select extends Component
{
    public function __construct(
        public string $name,
        public array $options = [],
        public string|array|null $values = null,
        public ?string $errorFor = null,
    )
    {
        $this->values = old($name, $this->values ?? '');

        if (! is_array($this->values)){
            $this->values = [$this->values];
        }

        $this->errorFor = $errorFor ?? Str::remove('[]', $this->name);
    }

    public function render(): View
    {
        return view('components.forms.select');
    }
}
