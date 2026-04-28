<?php

namespace App\View\Components\Ampp\Deals;

use App\Models\Deal;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(
        public Deal $deal
    ){}

    public function render(): View
    {
        return view('components.ampp.deals.card');
    }
}
