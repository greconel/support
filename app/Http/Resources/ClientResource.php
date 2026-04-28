<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Client */
class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'email' => $this->email,
            'phone' => $this->phone,
            'vat' => $this->vat,
            'street' => $this->street,
            'number' => $this->number,
            'postal' => $this->postal,
            'city' => $this->city,
            'country' => $this->country,
            'country_code' => $this->country_code,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'description' => $this->description,
            'address' => $this->address,
            'full_name' => $this->full_name,
            'full_name_backwards' => $this->full_name_backwards,
            'full_name_with_company' => $this->full_name_with_company,
            'full_name_with_email' => $this->full_name_with_email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
