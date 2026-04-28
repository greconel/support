<?php

namespace App\Http\Controllers\Ampp\Clients;

use App\Http\Controllers\Controller;
use App\Models\Client;

class RestoreClientController extends Controller
{
    public function __invoke($id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        $this->authorize('restore', $client);

        $client->restore();

        return redirect()->back();
    }
}
