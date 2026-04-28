<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientContact;

class CreateClientContactController extends Controller
{
    public function __invoke(Client $client)
    {
        $this->authorize('create', ClientContact::class);

        return view('ampp.clientContacts.create', [
            'client' => $client
        ]);
    }
}
