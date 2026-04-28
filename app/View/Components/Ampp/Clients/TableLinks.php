<?php

namespace App\View\Components\Ampp\Clients;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableLinks extends Component
{
    public int $active;
    public int $archived;

    public function __construct()
    {
        $this->active = Client::count();
        $this->archived = Client::onlyTrashed()->count();
    }

    public function render(): View
    {
        return view('components.ampp.clients.table-links');
    }
}
