<?php

namespace App\Jobs;

use App\Actions\Clearfacts\UploadToClearfactsAction;
use App\Enums\ClearfactsInvoiceType;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\Ampp\ClearfactsUploadedInvoicesNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UploadBulkInvoicesToClearfactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Collection $invoices
    ){}

    public function handle(UploadToClearfactsAction $uploadToClearfactsAction)
    {
        /** @var Invoice $invoice */
        foreach ($this->invoices as $invoice) {
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
                continue;
            }

            $data = $response->collect();

            $uuid = data_get($data, 'data.uploadFile.uuid');

            if (! $uuid){
                continue;
            }

            $invoice->update([
                'clearfacts_id' => $uuid,
                'sent_to_clearfacts_at' => now()
            ]);
        }

        $this->user->notify(new ClearfactsUploadedInvoicesNotification($this->invoices));
    }
}
