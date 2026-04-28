<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\Exports\ClientContactExport;
use App\Http\Controllers\Controller;
use App\Models\ClientContact;

class ExportClientContactsController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', ClientContact::class);

        return (new ClientContactExport)->download('client_contacts.xlsx');
    }
}
