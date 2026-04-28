<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Exports\ClientExport;
use App\Http\Controllers\Controller;
use App\Models\Client;

class ExportClientsController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Client::class);

        return (new ClientExport)->download('clients.xlsx');
    }
}
