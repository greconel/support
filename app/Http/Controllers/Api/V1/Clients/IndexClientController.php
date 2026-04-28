<?php

namespace App\Http\Controllers\Api\V1\Clients;

use App\Http\Resources\ClientCollection;
use App\Models\Client;

class IndexClientController
{
    /**
     * Get all clients
     *
     * @group Clients
     * @header Accept application/json
     *
     */
    public function __invoke()
    {
        return new ClientCollection(Client::all());
    }
}
