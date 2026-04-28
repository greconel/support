<?php

namespace App\Http\Controllers\Ampp\GdprMessages;

use App\DataTables\Ampp\GdprMessageDataTable;
use App\Http\Controllers\Controller;
use App\Models\GdprMessage;

class IndexGdprMessageController extends Controller
{
    public function __invoke(GdprMessageDataTable $dataTable)
    {
        $this->authorize('viewAny', GdprMessage::class);

        return $dataTable->render('ampp.gdprMessages.index');
    }
}
