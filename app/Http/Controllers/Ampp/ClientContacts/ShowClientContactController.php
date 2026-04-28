<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Controller;
use App\Models\ClientContact;

class ShowClientContactController extends Controller
{
    public function __invoke(ClientContact $clientContact)
    {
        $this->authorize('view', $clientContact);

        return view('ampp.clientContacts.show', [
            'client' => $clientContact->client,
            'contact' => $clientContact
        ]);
    }
}
