<?php

namespace App\Http\Controllers\Ampp\GdprAudits;

use App\Http\Controllers\Controller;
use App\Models\GdprAudit;

class EditGdprAuditController extends Controller
{
    public function __invoke(GdprAudit $gdprAudit)
    {
        $this->authorize('create', $gdprAudit);

        return view('ampp.gdprAudits.edit', [
            'audit' => $gdprAudit
        ]);
    }
}
