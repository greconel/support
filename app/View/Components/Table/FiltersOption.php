<?php

namespace App\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\QueryString\QueryString;

class FiltersOption extends Component
{
    public function __construct(
        public string $state,
        public string $value,
        public QueryString $queryString,
        public int $count = 0,
        public ?string $description = null,
    ) {
        $this->queryString = new QueryString(urldecode(request()->fullUrl()));
    }

    public function href(): string
    {
        return $this->queryString->toggle($this->state, $this->value);
    }

    public function isActive(): bool
    {
        return $this->queryString->isActive($this->state, $this->value);
    }

    public function render(): View
    {
        return view('components.table.filters-option');
    }
}
