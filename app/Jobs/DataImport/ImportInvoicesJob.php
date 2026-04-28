<?php

namespace App\Jobs\DataImport;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $invoices = DB::connection('old_ampp')->table('fac_invoices')->get();

        foreach ($invoices as $invoice) {
            Invoice::unguard();

            $newInvoice = Invoice::make([
                'id' => $invoice->id,
                'client_id' => $invoice->client_id,
                'clearfacts_id' => $invoice->clearfacts_id,
                'number' => $invoice->number,
                'ogm' => $invoice->structured_message,
                'status' => $this->getStatus($invoice->status),
                'amount' => $invoice->amount,
                'amount_with_vat' => $invoice->amount_vat,
                'pdf_comment' => $invoice->comment,
                'expiration_date' => $invoice->expiration_date,
                'custom_created_at' => $invoice->created_at,
                'sent_to_clearfacts_at' => $invoice->clearfacts_id ? $invoice->updated_at : null,
                'paid_at' => $invoice->status == 'paid' ? $invoice->updated_at : null,
                'created_at' => $invoice->created_at,
                'updated_at' => $invoice->updated_at
            ]);

            if (! $newInvoice->ogm){
                $newInvoice->ogm = $newInvoice->generateOgm();
            }

            $newInvoice->saveQuietly();

            Invoice::reguard();

            $lines = DB::connection('old_ampp')
                ->table('fac_invoice_lines')
                ->where('fac_invoice_id', '=', $invoice->id)
                ->get();

            foreach ($lines as $line){
                $newInvoice->billingLines()->create([
                    'type' => $line->type,
                    'order' => $line->order,
                    'text' => $line->text,
                    'price' => $line->price,
                    'subtotal' => $line->subtotal,
                    'amount' => $line->amount,
                    'vat' => $line->vat,
                    'discount' => $line->discount,
                    'description' => $line->description,
                    'created_at' => $line->created_at,
                    'updated_at' => $line->updated_at
                ]);
            }

            if ($lines->count() == 0){
                $newInvoice->billingLines()->create([
                    'type' => 'text',
                    'order' => 1,
                    'text' => 'Totaalbedrag',
                    'price' => $newInvoice->amount,
                    'subtotal' => $newInvoice->amount,
                    'amount' => 1,
                    'vat' => 21,
                    'created_at' => $newInvoice->created_at,
                    'updated_at' => $newInvoice->updated_at
                ]);
            }

            $file = DB::connection('old_ampp')
                ->table('fac_invoice_uploads')
                ->where('fac_invoice_id', '=', $invoice->id)
                ->first();

            if (! $file || app()->environment('local')){
                continue;
            }

            $newInvoice
                ->addMediaFromDisk("storage/app/$file->path", 'old_ampp_ftp')
                ->usingName($file->name)
                ->toMediaCollection('files', 'private')
            ;
        }
    }

    private function getStatus(string $status): InvoiceStatus
    {
        return match ($status){
            'pending' => InvoiceStatus::Draft,
            'sent' => InvoiceStatus::Sent,
            'reminder' => InvoiceStatus::Reminder,
            'paid' => InvoiceStatus::Paid,
        };
    }
}
