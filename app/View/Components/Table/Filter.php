<?php

namespace App\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Filter extends Component
{
    public function __construct(
        public string $tableId,
        public string $placeholder = ''
    ){}

    public function render(): View
    {
        return view('components.table.filter');
    }
}
