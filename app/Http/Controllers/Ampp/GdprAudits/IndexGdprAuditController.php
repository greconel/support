<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\DataTables\Ampp\GdprAuditDataTable;
use App\Http\Controllers\Controller;
use App\Models\GdprAudit;

class IndexGdprAuditController extends Controller
{
    public function __invoke(GdprAuditDataTable $dataTable)
    {
        $this->authorize('viewAny', GdprAudit::class);

        return $dataTable->render('ampp.gdprAudits.index');
    }
}
