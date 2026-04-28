<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BillingLines */
class BillingLineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'order' => $this->order,
            'text' => $this->text,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'amount' => $this->amount,
            'vat' => $this->vat,
            'discount' => $this->discount,
            'description' => $this->description,
            'amount_formatted' => $this->amount_formatted,
            'price_formatted' => $this->price_formatted,
            'sub_total_formatted' => $this->sub_total_formatted,
            'vat_formatted' => $this->vat_formatted,
        ];
    }
}
