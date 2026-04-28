<?php

namespace App\Jobs\DataImport;

use App\Enums\QuotationStatus;
use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportQuotationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $quotations = DB::connection('old_ampp')->table('fac_quotations')->get();

        foreach ($quotations as $quotation) {
            Quotation::unguard();

            $newQuotation = Quotation::create([
                'id' => $quotation->id,
                'client_id' => $quotation->client_id,
                'number' => $quotation->number,
                'status' => $this->getStatus($quotation->status),
                'amount' => $quotation->amount,
                'amount_with_vat' => $quotation->amount_vat,
                'notes' => null,
                'pdf_comment' => $quotation->comment,
                'expiration_date' => $quotation->expiration_date,
                'custom_created_at' => $quotation->created_at,
                'accepted_at' => $quotation->status == 'accepted' ? $quotation->updated_at : null,
                'created_at' => $quotation->created_at,
                'updated_at' => $quotation->updated_at
            ]);

            Quotation::reguard();

            $lines = DB::connection('old_ampp')
                ->table('fac_quotation_lines')
                ->where('fac_quotation_id', '=', $quotation->id)
                ->get();

            foreach ($lines as $line){
                $newQuotation->billingLines()->create([
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
        }
    }

    private function getStatus(string $status): QuotationStatus
    {
        return match ($status){
            'draft' => QuotationStatus::Draft,
            'sent' => QuotationStatus::Sent,
            'reminder' => QuotationStatus::Reminder,
            'accepted' => QuotationStatus::Accepted,
            'rejected' => QuotationStatus::Rejected,
        };
    }
}
