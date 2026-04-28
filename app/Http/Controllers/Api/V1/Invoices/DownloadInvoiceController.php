<?php

namespace App\Http\Controllers\Api\V1\Invoices;

use App\Models\Invoice;
use Illuminate\Http\Request;

class DownloadInvoiceController
{
    /**
     * @hideFromAPIDocumentation
     */
    public function __invoke(Request $request, Invoice $invoice)
    {
        if (! $request->hasValidSignature()){
            abort(401);
        }

        $invoice->generatePdf();

        $invoice->refresh();

        $pdf = $invoice->getFirstMedia('pdf');

        return response()->download($pdf->getPath(), $pdf->name);
    }
}
