<?php

namespace App\View\Components\Ampp\Quotations\Show;

use App\Models\Quotation;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QuotationOverview extends Component
{
    public Quotation $quotation;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function render(): View
    {
        return view('components.ampp.quotations.show.quotation-overview');
    }
}
