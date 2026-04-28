<?php

namespace App\Http\Controllers\Ampp\Quotations;

use App\Http\Controllers\Controller;
use App\Models\Quotation;

class DestroyQuotationController extends Controller
{
    public function __invoke(Quotation $quotation)
    {
        $this->authorize('delete', Quotation::class);

        $quotation->delete();

        return redirect()
            ->action(IndexQuotationController::class)
            ->with('success', __('Successfully deleted quotation'))
        ;
    }
}
