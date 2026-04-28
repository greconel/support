<?php

namespace App\Http\Controllers\Admin\HelpMessages;

use App\DataTables\Admin\HelpMessageDataTable;
use App\Http\Controllers\Controller;
use App\Models\HelpMessage;

class IndexHelpMessageController extends Controller
{
    public function __invoke(HelpMessageDataTable $dataTable)
    {
        $this->authorize('viewAny', HelpMessage::class);

        return $dataTable->render('admin.helpMessages.index');
    }
}
