<?php

namespace App\Http\Controllers\Ampp\Invoices;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ampp\Invoices\UploadClearfactsBulkInvoiceRequest;
use App\Jobs\UploadBulkInvoicesToClearfactsJob;
use App\Models\Invoice;

class UploadClearfactsBulkInvoiceController extends Controller
{
    public function __invoke(UploadClearfactsBulkInvoiceRequest $request, UploadToClearfactsAction $uploadToClearfactsAction)
    {
        $this->authorize('create', Invoice::class);

        $invoices = Invoice::whereIn('id', $request->input('invoices'))->get();

        dispatch(new UploadBulkInvoicesToClearfactsJob(auth()->user(), $invoices));

        session()->flash('success', __('Invoices are uploading to Clearfacts.'));

        return redirect()->action(IndexClearfactsBulkInvoiceController::class);
    }
}
