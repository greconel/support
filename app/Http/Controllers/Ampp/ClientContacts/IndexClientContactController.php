<?php

namespace App\Http\Controllers\Ampp\ClientContacts;

use App\DataTables\Ampp\ClientContactDataTable;
use App\Http\Controllers\Controller;
use App\Models\ClientContact;

class IndexClientContactController extends Controller
{
    public function __invoke(ClientContactDataTable $dataTable)
    {
        $this->authorize('viewAny', ClientContact::class);

        return $dataTable->render('ampp.clientContacts.index');
    }
}
