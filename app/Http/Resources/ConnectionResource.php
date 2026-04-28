<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

/** @mixin \App\Models\Connection */
class ConnectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'in_use' => $this->in_use,
            'custom_attributes' => $this->custom_attributes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'file_name' => $this->file_name,
            'file_url' => URL::temporarySignedRoute('apiV1.connections.download', now()->addMinutes(5), ['connection' =>
                $this->id])
        ];
    }
}
