<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Enums\ClearfactsInvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice;

class UploadInvoiceToClearfactsController extends Controller
{
    public function __invoke(Invoice $invoice, UploadToClearfactsAction $uploadToClearfactsAction)
    {
        $invoice->generatePdf();

        $invoice->refresh();

        $pdf = $invoice->getFirstMedia('pdf')->getPath();

        $response = $uploadToClearfactsAction->execute(
            ClearfactsInvoiceType::Sale,
            $pdf,
            $invoice->file_name,
            $invoice->clearfacts_comment
        );

        if ($response->failed() || $response->collect()->has('errors')){
            session()->flash('error', __('Something went wrong, please contact our support.'));
            return redirect()->action(ShowInvoiceController::class, $invoice);
        }

        $data = $response->collect();

        $uuid = data_get($data, 'data.uploadFile.uuid');

        if (! $uuid){
            session()->flash('error', __('Invoice is uploaded but clearfacts\' response was incorrect, please contact our support.'));
            return redirect()->action(ShowInvoiceController::class, $invoice);
        }

        $invoice->update([
            'clearfacts_id' => $uuid,
            'sent_to_clearfacts_at' => now()
        ]);

        session()->flash('success', __('Invoice uploaded to clearfacts.'));
        return redirect()->action(ShowInvoiceController::class, $invoice);
    }
}
