<?php

namespace App\Http\Controllers\Ampp\HelpMessages;

use App\DataTables\Ampp\HelpMessageDataTable;
use App\Http\Controllers\Controller;
use App\Models\HelpMessage;

class IndexHelpMessageController extends Controller
{
    public function __invoke(HelpMessageDataTable $dataTable)
    {
        $this->authorize('viewAny', HelpMessage::class);

        return $dataTable->render('ampp.helpMessages.index');
    }
}
