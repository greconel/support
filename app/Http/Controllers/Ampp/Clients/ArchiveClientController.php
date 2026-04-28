<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ArchiveClientController extends Controller
{
    public function __invoke(Client $client)
    {
        $this->authorize('archive', $client);

        $client->delete();

        return redirect()->action(IndexClientController::class);
    }
}
