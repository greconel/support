<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Http\Controllers\Controller;
use App\Models\ClientContact;

class EditClientContactController extends Controller
{
    public function __invoke(ClientContact $clientContact)
    {
        $this->authorize('update', $clientContact);

        return view('ampp.clientContacts.edit', [
            'client' => $clientContact->client,
            'contact' => $clientContact
        ]);
    }
}
