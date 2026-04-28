<?php

namespace App\Http\Resources;

use App\Models\Invoice;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

/** @mixin Invoice */
class InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'number' => $this->number,
            'type' => $this->type,
            'ogm' => $this->ogm,
            'status' => $this->status,
            'amount' => $this->amount,
            'amount_with_vat' => $this->amount_with_vat,
            'notes' => $this->notes,
            'pdf_comment' => $this->pdf_comment,
            'expiration_date' => $this->expiration_date,
            'paid_at' => $this->paid_at,
            'amount_formatted' => $this->amount_formatted,
            'amount_with_vat_formatted' => $this->amount_with_vat_formatted,
            'custom_name' => $this->custom_name,
            'file_name' => $this->file_name,
            'file_name_reminder' => $this->file_name_reminder,
            'custom_created_at' => $this->custom_created_at,

            'billing_lines' => BillingLineResource::collection($this->whenLoaded('billingLines')),
            'download_invoice_url' => URL::temporarySignedRoute('apiV1.invoices.download', now()->addMinutes(5), ['invoice' => $this->id])
        ];
    }
}
